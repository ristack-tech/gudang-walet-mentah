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
                        'jumlah_box' => $item['jumlah_box'] ?? 0,
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
    public function transferToClop($stokGradingDetailId, $tipeBox, $berat)
    {
        return DB::transaction(function () use ($stokGradingDetailId, $tipeBox, $berat) {
            
            // Find the specific graded detail
            $stokGradingDetail = \App\Models\StokGradingDetail::with('stokGrading')->findOrFail($stokGradingDetailId);
            
            if ($stokGradingDetail->berat < $berat) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$stokGradingDetail->berat}g.");
            }

            // Deduct from the detail record
            $stokGradingDetail->update([
                'berat' => $stokGradingDetail->berat - $berat,
            ]);

            $stokGrading = $stokGradingDetail->stokGrading;
            
            // HAPUS PENGURANGAN total_gram_keseluruhan! 
            // Jika total_gram_keseluruhan dikurangi di sini saat transfer (padahal barangnya sukses masuk Clop), 
            // maka Sistem akan otomatis membal-kan saldo bahan Mentah ke Demak, sehingga stok Clop menjadi fiktif.
            // Biarkan total_gram_keseluruhan utuh mencatat histori keberhasilan Grading.

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
            
            // Calculate jumlah box based on Kapasitas Tipe Box
            $jumlahBox = 0;
            if ($tipeBox > 1) { // 1 was marked as 'Lainnya/Non-Kapasitas' in view
                // We use floor to get the full boxes. 
                $jumlahBox = floor($berat / $tipeBox);
            }
            
            StokBarangJadiDetail::create([
                'stok_barang_jadi_id' => $clopStok->id,
                'barang_id' => $stokGrading->barang_id,
                'nama_barang' => $fullNamaBarang,
                'jumlah_box' => $jumlahBox, // Now acts as full Box count
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
                $isTransferFromGlobal = ($stok->type === 'clop' && str_starts_with($stok->keterangan, 'Transfer dari Global Stok Grading'));
                $stokGrading = null;
                $stokGradingDetail = null;

                if ($isTransferFromGlobal) {
                    if (preg_match('/No Kartu: (.*) \/ (.*)$/', $stok->keterangan, $matches)) {
                        $noKartu = trim($matches[1]);
                        $grade = trim($matches[2]);
                        
                        $stokGrading = \App\Models\StokGrading::where('no_kartu', $noKartu)->first();
                        if ($stokGrading) {
                            $stokGradingDetail = \App\Models\StokGradingDetail::where('stok_grading_id', $stokGrading->id)
                                ->where('grade', $grade)
                                ->first();
                        }
                    }
                }

                foreach ($data['details'] as $detailData) {
                    $detailRow = StokBarangJadiDetail::where('stok_barang_jadi_id', $stok->id)
                                ->where('id', $detailData['id'])
                                ->first();

                    if ($detailRow) {
                        $newBerat = (float) $detailData['berat'];
                        
                        if ($isTransferFromGlobal && $stokGradingDetail) {
                            $deltaBerat = $newBerat - $detailRow->berat;
                            
                            if ($deltaBerat > 0) {
                                // We are increasing CLOP. Need to deduct from parent.
                                if ($stokGradingDetail->berat < $deltaBerat) {
                                    throw new \Exception("Stok Global tidak mencukupi untuk penambahan. Hanya tersedia: {$stokGradingDetail->berat}g di Global.");
                                }
                                
                                $stokGradingDetail->update([
                                    'berat' => $stokGradingDetail->berat - $deltaBerat,
                                ]);
                                
                                // HAPUS pemotongan total_gram_keseluruhan agar stok mentah tidak refund ke Demak
                            } elseif ($deltaBerat < 0) {
                                // We are returning to CLOP. Add abs difference to parent.
                                $returnGram = abs($deltaBerat);
                                
                                $stokGradingDetail->update([
                                    'berat' => $stokGradingDetail->berat + $returnGram,
                                ]);
                                
                                // HAPUS penambahan total_gram_keseluruhan agar stok mentah tidak tercatat ulang
                            }
                        }

                        $detailRow->update([
                            // If PCS wasn't submitted (e.g. we removed it), preserve old value
                            'jumlah_box' => $detailData['jumlah_box'] ?? $detailRow->jumlah_box,
                            'berat' => $newBerat,
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
                            
                            // Restore StokGradingDetail
                            $detail->update([
                                'berat' => $detail->berat + $totalBeratTransfer,
                            ]);
                            // HAPUS penambahan total_gram_keseluruhan agar selaras dengan fix phantom stock
                            // Total_gram_keseluruhan Induk harus dibiarkan stabil murni sesuai hasil grading.
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
        // Get all details that belong to clop stocks and have available boxes
        $clopStocks = StokBarangJadiDetail::whereHas('stokBarangJadi', function ($query) {
                $query->where('type', 'clop');
            })
            ->where('jumlah_box', '>', 0)
            ->where('berat', '>', 0)
            ->select('id', 'nama_barang', 'berat as total_berat', 'jumlah_box as total_pcs')
            ->get();

        $availableStocks = [];

        foreach ($clopStocks as $clop) {
            $availableStocks[$clop->id] = [
                'id' => $clop->id,
                'nama_barang' => $clop->nama_barang,
                'available_berat' => $clop->total_berat,
                'available_pcs' => $clop->total_pcs,
            ];
        }

        return $availableStocks;
    }
}
