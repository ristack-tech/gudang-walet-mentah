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
                'pcs_awal' => $data['pcs_awal'] ?? 0,
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
            'pcs_awal' => $stokGrading->pcs_awal,
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
            $stokGrading = StokGrading::findOrFail($id);
            // Optionally delete details if not using onDelete('cascade') or want soft deletes cascading
            $stokGrading->details()->delete();
            $stokGrading->delete();
            
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
                'pcs_awal' => $data['pcs_awal'] ?? $stokGrading->pcs_awal,
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
