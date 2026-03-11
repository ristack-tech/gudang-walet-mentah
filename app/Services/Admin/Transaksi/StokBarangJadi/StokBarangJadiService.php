<?php

namespace App\Services\Admin\Transaksi\StokBarangJadi;

use App\Models\StokBarangJadi;
use App\Models\StokBarangJadiDetail;
use Illuminate\Support\Facades\DB;

class StokBarangJadiService
{
    /**
     * Get Paginated Stok Barang Jadi
     */
    public function getPaginatedStok($type = 'global', $perPage = 10)
    {
        return StokBarangJadi::with('details')
            ->where('type', $type)
            ->orderBy('tanggal', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Store a new Stok Barang Jadi record.
     */
    public function storeStok(array $data)
    {
        return DB::transaction(function () use ($data) { // Keep data if making a manual generic form
            // Save parent record
            $stok = StokBarangJadi::create([
                'batch' => $data['batch'] ?? 'SBJ-' . time(),
                'tanggal' => $data['tanggal'] ?? now()->toDateString(),
                'type' => $data['type'] ?? 'global',
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Save details
            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    StokBarangJadiDetail::create([
                        'stok_barang_jadi_id' => $stok->id,
                        'nama_barang' => $item['nama_barang'],
                        'jumlah_pcs' => $item['jumlah_pcs'] ?? 0,
                        'berat' => $item['berat'] ?? 0,
                    ]);
                }
            }

            return $stok;
        });
    }

    /**
     * Transfer stock from global to clop. 
     */
    public function transferToClop($stokGradingDetailId, $jumlah, $berat)
    {
        return DB::transaction(function () use ($stokGradingDetailId, $jumlah, $berat) {
            
            // Find the specific graded detail
            $stokGradingDetail = \App\Models\StokGradingDetail::with('stokGrading')->findOrFail($stokGradingDetailId);
            
            if ($stokGradingDetail->berat < $berat || $stokGradingDetail->box < $jumlah) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$stokGradingDetail->berat}g, {$stokGradingDetail->box} pcs.");
            }

            // Deduct from the detail record
            $stokGradingDetail->update([
                'berat' => $stokGradingDetail->berat - $berat,
                'box' => $stokGradingDetail->box - $jumlah,
            ]);

            // Deduct from the overall global parent record
            $stokGrading = $stokGradingDetail->stokGrading;
            $stokGrading->update([
                'total_gram_keseluruhan' => max(0, $stokGrading->total_gram_keseluruhan - $berat),
                'total_box_keseluruhan' => max(0, $stokGrading->total_box_keseluruhan - $jumlah),
            ]);

            // Add new "clop" record 
            $clopStok = StokBarangJadi::create([
                'batch' => 'TRF-CLOP-' . date('YmdHis'),
                'tanggal' => now()->toDateString(),
                'type' => 'clop',
                'keterangan' => 'Transfer dari Global Stok Grading No Kartu: '.$stokGrading->no_kartu.' / '.$stokGradingDetail->grade,
            ]);

            $itemName = $stokGrading->namaBarang ? $stokGrading->namaBarang->nama_barang : 'Unknown Item';
            $gradeType = strtoupper(str_replace('_', ' ', $stokGradingDetail->type ?? ''));
            $gradeName = $stokGradingDetail->grade;
            
            $fullNamaBarang = "{$itemName} - {$gradeType} ({$gradeName})";
            
            StokBarangJadiDetail::create([
                'stok_barang_jadi_id' => $clopStok->id,
                'barang_id' => $stokGrading->barang_id,
                'nama_barang' => $fullNamaBarang,
                'jumlah_pcs' => $jumlah,
                'berat' => $berat,
            ]);

            return $clopStok;
        });
    }

    /**
     * Update an existing Stok Barang Jadi record.
     */
    public function updateStok($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $stok = StokBarangJadi::with('details')->findOrFail($id);
            
            // Update parent record
            $stok->update([
                'tanggal' => $data['tanggal'] ?? $stok->tanggal,
                'keterangan' => $data['keterangan'] ?? $stok->keterangan,
            ]);

            // If we are modifying details heavily (or just updating their weight/pcs)
            // The request validations ensures we receive 'details' array with IDs
            if (!empty($data['details'])) {
                foreach ($data['details'] as $detailData) {
                    $detailRow = StokBarangJadiDetail::where('stok_barang_jadi_id', $stok->id)
                                ->where('id', $detailData['id'])
                                ->first();

                    if ($detailRow) {
                        $detailRow->update([
                            'jumlah_pcs' => $detailData['jumlah_pcs'],
                            'berat' => $detailData['berat'],
                        ]);
                    }
                }
            }

            return $stok;
        });
    }

    /**
     * Delete a Stok Barang Jadi record.
     */
    public function deleteStok($id)
    {
        return DB::transaction(function () use ($id) {
            $stok = StokBarangJadi::with('details')->findOrFail($id);
            
            // Check if this was a transfer from Global Stok Grading
            if ($stok->type === 'clop' && str_starts_with($stok->keterangan, 'Transfer dari Global Stok Grading')) {
                // Determine the No Kartu and Grade involved
                // Format: Transfer dari Global Stok Grading No Kartu: [no_kartu] / [grade]
                if (preg_match('/No Kartu: (.*) \/ (.*)$/', $stok->keterangan, $matches)) {
                    $noKartu = trim($matches[1]);
                    $grade = trim($matches[2]);
                    
                    $stokGrading = \App\Models\StokGrading::where('no_kartu', $noKartu)->first();
                    
                    if ($stokGrading) {
                        $detail = \App\Models\StokGradingDetail::where('stok_grading_id', $stokGrading->id)
                            ->where('grade', $grade)
                            ->first();
                            
                        if ($detail) {
                            $totalBeratTransfer = $stok->details->sum('berat');
                            $totalBoxTransfer = $stok->details->sum('jumlah_pcs');
                            
                            // Restore StokGradingDetail
                            $detail->update([
                                'berat' => $detail->berat + $totalBeratTransfer,
                                'box' => $detail->box + $totalBoxTransfer,
                            ]);
                            
                            // Restore parent StokGrading overall amount
                            $stokGrading->update([
                                'total_gram_keseluruhan' => $stokGrading->total_gram_keseluruhan + $totalBeratTransfer,
                                'total_box_keseluruhan' => $stokGrading->total_box_keseluruhan + $totalBoxTransfer,
                            ]);
                        }
                    }
                }
            }
            
            $stok->details()->delete();
            $stok->delete();
            
            return true;
        });
    }
    public function getAvailableClopStocks()
    {
        // Get all details that belong to clop stocks
        $clopStocks = StokBarangJadiDetail::whereHas('stokBarangJadi', function ($query) {
                $query->where('type', 'clop');
            })
            ->select('id', 'nama_barang', 'berat as total_berat', 'jumlah_pcs as total_pcs')
            ->get()
            ->keyBy('id');

        // Get all exported stocks grouped by stok_barang_jadi_detail_id
        $exportStocks = \App\Models\ExportBarang::select('stok_barang_jadi_detail_id', DB::raw('SUM(berat) as total_berat'), DB::raw('SUM(pcs) as total_pcs'))
            ->groupBy('stok_barang_jadi_detail_id')
            ->get()
            ->keyBy('stok_barang_jadi_detail_id');

        $availableStocks = [];

        foreach ($clopStocks as $detailId => $clop) {
            $export = $exportStocks->get($detailId);
            $exportedBerat = $export ? $export->total_berat : 0;
            $exportedPcs = $export ? $export->total_pcs : 0;

            $availableBerat = max(0, $clop->total_berat - $exportedBerat);
            $availablePcs = max(0, $clop->total_pcs - $exportedPcs);

            if ($availableBerat > 0) {
                $availableStocks[$detailId] = [
                    'id' => $detailId,
                    'nama_barang' => $clop->nama_barang,
                    'available_berat' => $availableBerat,
                    'available_pcs' => $availablePcs,
                ];
            }
        }

        return $availableStocks;
    }
}
