<?php

namespace App\Services\Admin\Transaksi\StokGrading;

use App\Models\StokGrading;
use App\Models\StokGradingDetail;
use Illuminate\Support\Facades\DB;

class StokGradingService
{
    /**
     * Store a new Stok Grading record.
     */
    public function storeGrading(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Calculate totals
            $totals = [
                'gram_lempeng_bersih' => 0,
                'pcs_lempeng_bersih' => 0,
                'box_lempeng_bersih' => 0,
                'gram_lempeng_kotor' => 0,
                'pcs_lempeng_kotor' => 0,
                'box_lempeng_kotor' => 0,
                'gram_mangkok' => 0,
                'pcs_mangkok' => 0,
                'box_mangkok' => 0,
            ];

            foreach ($data['grades'] as $grade) {
                if ($grade['berat'] > 0 || $grade['box'] > 0) {
                    if ($grade['type'] === 'lempeng_bersih') {
                        $totals['gram_lempeng_bersih'] += (float) $grade['berat'];
                        $totals['box_lempeng_bersih'] += (int) $grade['box'];
                    } elseif ($grade['type'] === 'lempeng_kotor') {
                        $totals['gram_lempeng_kotor'] += (float) $grade['berat'];
                        $totals['box_lempeng_kotor'] += (int) $grade['box'];
                    } elseif ($grade['type'] === 'mangkok') {
                        $totals['gram_mangkok'] += (float) $grade['berat'];
                        $totals['box_mangkok'] += (int) $grade['box'];
                    }
                }
            }

            $totalGramKeseluruhan = $totals['gram_lempeng_bersih'] + $totals['gram_lempeng_kotor'] + $totals['gram_mangkok'];
            $totalPcsKeseluruhan = 0;
            $totalBoxKeseluruhan = $totals['box_lempeng_bersih'] + $totals['box_lempeng_kotor'] + $totals['box_mangkok'];

            // Save parent record
            $stokGrading = StokGrading::create([
                'barang_id' => $data['barang_id'] ?? null,
                'sumber_stok' => $data['sumber_stok'] ?? null,
                'no_kartu' => 'STG-' . date('YmdHis'),
                'tanggal' => now()->toDateString(),
                'partai' => $data['partai'] ?? '-',
                'pekerja' => '-', // Default until workers are fully implemented
                'box_awal' => $data['box_awal'] ?? 0,
                'total_gram_lempeng_bersih' => $totals['gram_lempeng_bersih'],
                'total_pcs_lempeng_bersih' => 0,
                'total_box_lempeng_bersih' => $totals['box_lempeng_bersih'],
                'total_gram_lempeng_kotor' => $totals['gram_lempeng_kotor'],
                'total_pcs_lempeng_kotor' => 0,
                'total_box_lempeng_kotor' => $totals['box_lempeng_kotor'],
                'total_gram_mangkok' => $totals['gram_mangkok'],
                'total_pcs_mangkok' => 0,
                'total_box_mangkok' => $totals['box_mangkok'],
                'total_gram_keseluruhan' => $totalGramKeseluruhan,
                'total_pcs_keseluruhan' => 0,
                'total_box_keseluruhan' => $totalBoxKeseluruhan,
            ]);

            // Save details
            foreach ($data['grades'] as $grade) {
                if ($grade['berat'] > 0 || $grade['box'] > 0) {
                    StokGradingDetail::create([
                        'stok_grading_id' => $stokGrading->id,
                        'type' => $grade['type'],
                        'grade' => $grade['name'],
                        'berat' => $grade['berat'] ?? 0,
                        'pcs' => 0,
                        'box' => $grade['box'] ?? 0,
                    ]);
                }
            }

            return $stokGrading;
        });
    }

    /**
     * Get a Stok Grading record with its details mapped by type for view formatting.
     */
    public function getGradingFormatted($id)
    {
        $stokGrading = StokGrading::with('details')->findOrFail($id);
        
        $lempengBersih = [];
        $lempengKotor = [];
        $mangkok = [];

        foreach ($stokGrading->details as $detail) {
            $mappedDetail = [
                'grade' => $detail->grade,
                'berat' => $detail->berat,
                'box' => $detail->box,
            ];

            if ($detail->type === 'lempeng_bersih') {
                $lempengBersih[] = $mappedDetail;
            } elseif ($detail->type === 'lempeng_kotor') {
                $lempengKotor[] = $mappedDetail;
            } elseif ($detail->type === 'mangkok') {
                $mangkok[] = $mappedDetail;
            }
        }

        return [
            'id' => $stokGrading->id,
            'barang_id' => $stokGrading->barang_id,
            'sumber_stok' => $stokGrading->sumber_stok,
            'no_kartu' => $stokGrading->no_kartu,
            'tanggal' => $stokGrading->tanggal,
            'partai' => $stokGrading->partai,
            'pekerja' => $stokGrading->pekerja,
            'box_awal' => $stokGrading->box_awal,
            'lempeng_bersih' => $lempengBersih,
            'lempeng_kotor' => $lempengKotor,
            'mangkok' => $mangkok,
            'totals' => [
                'lempeng_bersih' => [
                    'gram' => $stokGrading->total_gram_lempeng_bersih,
                    'pcs' => $stokGrading->total_pcs_lempeng_bersih,
                    'box' => $stokGrading->total_box_lempeng_bersih,
                ],
                'lempeng_kotor' => [
                    'gram' => $stokGrading->total_gram_lempeng_kotor,
                    'pcs' => $stokGrading->total_pcs_lempeng_kotor,
                    'box' => $stokGrading->total_box_lempeng_kotor,
                ],
                'mangkok' => [
                    'gram' => $stokGrading->total_gram_mangkok,
                    'pcs' => $stokGrading->total_pcs_mangkok,
                    'box' => $stokGrading->total_box_mangkok,
                ],
                'keseluruhan' => [
                    'gram' => $stokGrading->total_gram_keseluruhan,
                    'pcs' => $stokGrading->total_pcs_keseluruhan,
                    'box' => $stokGrading->total_box_keseluruhan,
                ],
            ]
        ];
    }

    /**
     * Delete a Stok Grading record and its details.
     */
    public function deleteGrading($id)
    {
        return DB::transaction(function () use ($id) {
            // BUG-20, BUG-22: Lock parent record to prevent race condition
            $stokGrading = StokGrading::lockForUpdate()->findOrFail($id);

            // Cek sisa saldo yang tertera (yang belum pernah ditarik ke Clop)
            $totalSisaBerat = $stokGrading->details()->sum('berat');

            // BUG-25: Fixed - return proper message when nothing to delete
            if ($totalSisaBerat == 0) {
                // Delete the empty grading record since there's no stock left
                $stokGrading->details()->delete();
                $stokGrading->delete();
                return [
                    'success' => true,
                    'message' => 'Grading berhasil dihapus karena semua stok sudah dipindahkan.',
                    'restored_gram' => 0,
                ];
            }
            
            // Hitung gramasi yang SESUNGGUHNYA SUDAH pernah dipakai (sudah ditransfer)
            $totalGramSistemOriginal = $stokGrading->total_gram_keseluruhan;
            $gramSudahDigunakan = max(0, $totalGramSistemOriginal - $totalSisaBerat);

            if ($gramSudahDigunakan == 0) {
                // Semua barang masih UTUH (Belum ada satupun detail yang ditransfer Clop)
                // Lakukan Full Delete fisik untuk mengeleminasi dokumen 100%
                $stokGrading->details()->forceDelete();
                $stokGrading->delete();
            } else {
                // Partial Rollback (Batal Sisa)
                // Kita kurangi total_gram pada Induk berdasarkan Sisa Berat yang dibatalkan
                $sisaBersih = 0;
                $sisaKotor = 0;
                $sisaMangkok = 0;
                
                foreach ($stokGrading->details as $detail) {
                    if ($detail->type === 'lempeng_bersih') $sisaBersih += $detail->berat;
                    elseif ($detail->type === 'lempeng_kotor') $sisaKotor += $detail->berat;
                    elseif ($detail->type === 'mangkok') $sisaMangkok += $detail->berat;
                    
                    // Nolkan sisa saldo agar data bersih dari Tab Global (Global Mengakui Tidak Ada Sisa)
                    $detail->update(['berat' => 0]);
                }
                
                // Update parent master totals (ini akan otomatis me-refund Demak Raw Material sejumlah Sisa yg batal)
                $stokGrading->update([
                    'total_gram_lempeng_bersih' => max(0, $stokGrading->total_gram_lempeng_bersih - $sisaBersih),
                    'total_gram_lempeng_kotor' => max(0, $stokGrading->total_gram_lempeng_kotor - $sisaKotor),
                    'total_gram_mangkok' => max(0, $stokGrading->total_gram_mangkok - $sisaMangkok),
                    'total_gram_keseluruhan' => max(0, $stokGrading->total_gram_keseluruhan - $totalSisaBerat),
                ]);
            }
            
            return true;
        });
    }

    /**
     * Update an existing Stok Grading record and its details.
     */
    public function updateGrading($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $stokGrading = StokGrading::findOrFail($id);

            // Calculate totals
            $totals = [
                'gram_lempeng_bersih' => 0,
                'pcs_lempeng_bersih' => 0,
                'box_lempeng_bersih' => 0,
                'gram_lempeng_kotor' => 0,
                'pcs_lempeng_kotor' => 0,
                'box_lempeng_kotor' => 0,
                'gram_mangkok' => 0,
                'pcs_mangkok' => 0,
                'box_mangkok' => 0,
            ];

            foreach ($data['grades'] as $grade) {
                if ($grade['berat'] > 0 || $grade['box'] > 0) {
                    if ($grade['type'] === 'lempeng_bersih') {
                        $totals['gram_lempeng_bersih'] += (float) $grade['berat'];
                        $totals['box_lempeng_bersih'] += (int) $grade['box'];
                    } elseif ($grade['type'] === 'lempeng_kotor') {
                        $totals['gram_lempeng_kotor'] += (float) $grade['berat'];
                        $totals['box_lempeng_kotor'] += (int) $grade['box'];
                    } elseif ($grade['type'] === 'mangkok') {
                        $totals['gram_mangkok'] += (float) $grade['berat'];
                        $totals['box_mangkok'] += (int) $grade['box'];
                    }
                }
            }

            $totalGramKeseluruhan = $totals['gram_lempeng_bersih'] + $totals['gram_lempeng_kotor'] + $totals['gram_mangkok'];
            $totalPcsKeseluruhan = 0;
            $totalBoxKeseluruhan = $totals['box_lempeng_bersih'] + $totals['box_lempeng_kotor'] + $totals['box_mangkok'];

            // Update parent record
            $stokGrading->update([
                'sumber_stok' => $data['sumber_stok'] ?? $stokGrading->sumber_stok,
                'box_awal' => $data['box_awal'] ?? $stokGrading->box_awal,
                'total_gram_lempeng_bersih' => $totals['gram_lempeng_bersih'],
                'total_pcs_lempeng_bersih' => 0,
                'total_box_lempeng_bersih' => $totals['box_lempeng_bersih'],
                'total_gram_lempeng_kotor' => $totals['gram_lempeng_kotor'],
                'total_pcs_lempeng_kotor' => 0,
                'total_box_lempeng_kotor' => $totals['box_lempeng_kotor'],
                'total_gram_mangkok' => $totals['gram_mangkok'],
                'total_pcs_mangkok' => 0,
                'total_box_mangkok' => $totals['box_mangkok'],
                'total_gram_keseluruhan' => $totalGramKeseluruhan,
                'total_pcs_keseluruhan' => 0,
                'total_box_keseluruhan' => $totalBoxKeseluruhan,
            ]);

            // Clear old details and recreate
            $stokGrading->details()->forceDelete();

            // Save new details
            foreach ($data['grades'] as $grade) {
                if ($grade['berat'] > 0 || $grade['box'] > 0) {
                    StokGradingDetail::create([
                        'stok_grading_id' => $stokGrading->id,
                        'type' => $grade['type'],
                        'grade' => $grade['name'],
                        'berat' => $grade['berat'] ?? 0,
                        'pcs' => 0,
                        'box' => $grade['box'] ?? 0,
                    ]);
                }
            }

            return $stokGrading;
        });
    }
}
