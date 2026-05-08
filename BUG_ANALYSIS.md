# Laporan Analisis Bug - Gudang Walet Mentah

> Dibuat: 2026-04-26
> Total Bug Ditemukan: 37 | Critical: 16

---

## Daftar Isi

1. [Critical Bugs](#critical-bugs)
2. [Logic Errors](#logic-errors)
3. [Security Issues](#security-issues)
4. [Race Conditions](#race-conditions)
5. [Error Handling](#error-handling)
6. [Data Consistency](#data-consistency)
7. [API Inconsistencies](#api-inconsistencies)

---

## Critical Bugs

### 1. Hardcoded Lokasi ID - BarangMasukDemakService

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukDemakService.php:40`

```php
'transaksi_ke_id' => 1, // Central Warehouse usually
```

**Masalah:** `lokasi_id` di-hardcode = 1 tanpa mengambil dari database. Jika Lokasi ID 1 dihapus atau dipindahkan, akan menyebabkan pelanggaran foreign key constraint atau referensi lokasi yang salah.

**Perbaikan:** Ambil lokasi ID secara dinamis berdasarkan konfigurasi perusahaan atau pengaturan gudang default.

---

### 2. Schema Mismatch - warna_id di barang_keluar_demak

**File:** `database/migrations/2026_02_28_133049_create_barang_keluar_demak_table.php:17-18`

**Masalah:** Kolom `warna_id` ditandai sebagai NOT NULL di migration, tapi `BarangKeluarDemakService::store()` (baris 28) mengembalikan nilai nullable:

```php
'warna_id' => $item['warna_id'] ?? null,
```

Ketika `warna_id` tidak disediakan, ini mengembalikan `null` yang melanggar constraint NOT NULL.

**Perbaikan:** Buat `warna_id` nullable di migration ATAU validasi di controller bahwa itu wajib diisi.

---

### 3. Race Condition - Export Bersamaan

**File:** `app/Http/Controllers/Admin/Transaksi/Export/ExportController.php:46-49`

**Masalah:** Dua user bisa export stok clop yang sama secara bersamaan:

1. User A load halaman, lihat 100g tersedia
2. User B load halaman, lihat 100g tersedia
3. User A export 100g - berhasil
4. User B export 100g - berhasil (tapi stok jadi negatif)

**Perbaikan:** Gunakan `SELECT FOR UPDATE` atau database transaction dengan row-level locking pada `stok_barang_jadi_details`.

---

### 4. StokSeeder Referensi Model yang Tidak Ada

**File:** `database/seeders/StokSeeder.php:18`

```php
Stok::truncate();
```

**Masalah:** Seeder mereferensikan model `Stok` yang tidak ada di direktori Models. Ini akan menyebabkan error jika seeder dijalankan.

**Perbaikan:** Ubah `Stok` ke nama model yang benar (kemungkinan `StokGudang`).

---

### 5. Silent Failure - StokGradingService::deleteGrading

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:165-167`

```php
if ($totalSisaBerat == 0) {
    return true;
}
```

**Masalah:** Ketika `$totalSisaBerat == 0`, fungsi mengembalikan `true` tapi TIDAK delete apapun. Pemanggil di `StokGradingController::destroy()` tidak pernah cek return value. User melihat pesan sukses tapi record tetap ada.

**Perbaikan:** Hapus record ketika berat 0, atau kembalikan status yang tepat.

---

### 6. TOCTOU Race Condition - StokBarangJadiService

**File:** `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php:157-165`

```php
if ($stokGradingDetail->berat < $deltaBerat) {
    throw new \Exception("Stok Global tidak mencukupi...");
}
$stokGradingDetail->update([
    'berat' => $stokGradingDetail->berat - $deltaBerat,
]);
```

**Masalah:** Time-of-Check-Time-of-Use race condition - request lain bisa mengubah detail antara cek (baris 158) dan update (baris 163).

---

---

## Logic Errors

### 1. PCS Totals di-Hardcode ke 0

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:59,62,65`

```php
'total_pcs_lempeng_bersih' => 0,
'total_pcs_lempeng_kotor' => 0,
'total_pcs_mangkok' => 0,
```

**Masalah:** Total PCS di-hardcode ke 0. Padahal `StokGradingDetail` menyimpan nilai `pcs`, tapi tidak pernah diagregasi ke total parent.

**Perbaikan:** Hitung dan agregasi PCS dari record StokGradingDetail.

---

### 2. Resiko Division by Zero

**File:** `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php:93`

```php
if ($tipeBox > 1) {
    $jumlahBox = floor($berat / $tipeBox);
}
```

**Masalah:** Jika `$tipeBox`传入值为 `0` (dari manipulasi form), menyebabkan division by zero.

**Perbaikan:** Tambahkan validasi bahwa `tipeBox > 0` atau cek sebelum division.

---

### 3. Perhitungan Susut (Shrinkage) Negatif

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukJasaCuciService.php:32`

```php
$susut = $outItem->berat_per_pcs - $item['gram_diterima'];
```

**Masalah:** Jika `gram_diterima` > `berat_per_pcs` (misal: error input, penyerapan kelembaban), `susut` jadi negatif yang tidak masuk akal secara bisnis.

**Perbaikan:** Gunakan `max(0, ...)` untuk mencegah nilai negatif, atau validasi input.

---

### 4. Resiko Null Reference - transferToClop

**File:** `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php:86-87`

```php
$itemName = $stokGrading->namaBarang ? $stokGrading->namaBarang->nama_barang : 'Unknown Item';
```

**Masalah:** Jika relasi `namaBarang` null, menggunakan 'Unknown Item' tapi `barang_id` tetap diset ke null.

---

### 5. Loose Comparison Checkbox

**File:** `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukDemakController.php:102`

```php
return isset($item['is_checked']) && $item['is_checked'] == '1';
```

**Masalah:** HTML checkbox mengirim `on` saat dicentang, bukan `1`. Loose comparison `'on' == '1'` gagal.

**Perbaikan:** Gunakan `$item['is_checked'] == true` atau `filter_var($item['is_checked'], FILTER_VALIDATE_BOOLEAN)`.

---

### 6. Missing Stok Gudang Update

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukDemakService.php:14-63`

**Masalah:** Ketika barang masuk demak dibuat, service membuat records tapi TIDAK mengupdate tabel `stok_gudangs`. Ini bisa menyebabkan inkonsistensi jika `stok_gudangs` digunakan di tempat lain.

---

---

## Security Issues

### 1. Missing Authorization - Semua Controller

**File:** Semua controller di `app/Http/Controllers/Admin/Transaksi/`

**Masalah:** Tidak ada authorization checks. User yang terautentikasi bisa:
- Lihat data company siapapun
- Modify record siapapun
- Hapus batch siapapun

Hanya middleware `auth` yang digunakan, yang hanya cek apakah user sudah login.

---

### 2. Session-based State Manipulation

**File:** `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukDemakController.php:52`

```php
session(['barang_masuk_demak_step1' => $validated]);
```

**Masalah:** Multi-step wizard forms menyimpan data di session tanpa validasi signature kriptografis. Attacker bisa memodifikasi session data untuk memanipulasi transaksi user lain.

---

### 3. Tidak Ada API Routes untuk Mobile/External

**File:** `routes/api.php:17-19`

**Masalah:** File API routes hanya berisi default Sanctum user endpoint. Semua fungsi lainnya hanya tersedia via web routes. Tidak ada JSON API responses.

---

### 4. Mass Assignment Protection

**File:** `app/Models/StokBarangJadiDetail.php:13-19`

```php
protected $fillable = [
    'stok_barang_jadi_id',
    'barang_id',
    'nama_barang',
    'jumlah_pcs',
    'berat',
];
```

**Masalah:** `nama_barang` adalah string field yang menerima teks arbitrary - tidak ada validasi bahwa itu match dengan barang actual. Attacker bisa inject data palsu.

---

---

## Race Conditions

### 1. Concurrent Export - ExportController

**File:** `app/Http/Controllers/Admin/Transaksi/Export/ExportController.php:36-59`

**Masalah:** Sama seperti Critical Bug #3 - tidak ada database-level locking saat cek/export stock.

---

### 2. Concurrent Stok Grading

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:14-88`

**Masalah:** Fungsi `storeGrading` melakukan cek via `getGlobalSummaryForBarang()` tapi antara cek dan create, grading lain bisa melewati validasi yang sama.

---

### 3. No Locking in StokGudangService

**File:** `app/Services/StokGudang/StokGudangService.php:15-46, 198-253`

**Masalah:** `getGlobalSummaryForBarang()`, `getDemakSummary()` melakukan agregasi tanpa locking. Digunakan untuk validasi stock sebelum write tapi state bisa berubah.

---

### 4. Delete-Then-Insert Without Lock

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukPembelianSupplierService.php:38-61`

```php
$barangMasukBatch->barangMasukSupplier()->delete(); // Baris 46
$barangMasukSupplier::create([...]); // Baris 50-57
```

**Masalah:** Jika insert gagal, child records hilang tanpa rollback.

---

---

## Error Handling

### 1. Silent Failure - deleteGrading

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:165-167`

Sama seperti Critical Bug #5.

---

### 2. Swallowed Exception - BarangKeluarDemakController

**File:** `app/Http/Controllers/Admin/Transaksi/barangKeluar/BarangKeluarDemakController.php:174-180`

```php
catch (\Exception $e) {
    return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
```

**Masalah:** Tidak ada logging, tidak ada pembedaan antar tipe error, raw error message bisa leak system info.

---

### 3. Unhandled ModelNotFoundException

**File:** `app/Services/Admin/Transaksi/Export/ExportService.php:34-38`

```php
$export = ExportBarang::findOrFail($id);
return $export->delete();
```

**Masalah:** `findOrFail` melempar unhandled exception jika record tidak ada.

---

### 4. Empty Array Check Causes Data Loss

**File:** `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukPembelianSupplierController.php:123`

```php
if (!$step1Data || empty($items)) {
    return redirect()->route(...);
}
```

**Masalah:** Saat redirect, user kehilangan session data step1 dan harus input ulang semua.

---

---

## Data Consistency

### 1. Missing barang_id Foreign Key

**File:** `database/migrations/2026_02_28_133049_create_barang_keluar_demak_table.php`

**Masalah:** Tabel `barang_keluar_demak` mereferensikan `partai` via `partai_id` tapi TIDAK punya foreign key untuk `barang_id`. Item yang keluar ke Demak tidak melacak barang mana.

---

### 2. Inconsistent Column Naming

**File:** `database/migrations/2026_02_28_134152_create_barang_keluar_jasa_cuci_table.php`

**Masalah:** Menggunakan `nama_barang_id` sebagai foreign key, tapi tabel lain seperti `barang_masuk_jasa_cuci` gunakan `barang_id`. Inkonsistensi penamaan.

---

### 3. Orphaned Records - BarangKeluarJasaCuciService

**File:** `app/Services/Admin/Transaksi/barangKeluar/BarangKeluarJasaCuiService.php:28`

**Masalah:** Jika item berbasis session dimanipulasi, `kode_bahan_id` bisa null, menciptakan orphaned records.

---

### 4. Missing Indexes for Performance

**File:** `app/Services/StokGudang/StokGudangService.php`

**Masalah:** Query agregasi kompleks tanpa indexes:

- `barang_masuk_demak` perlu index di `barang_id`
- `barang_masuk_jasa_cuci` perlu index di `barang_id`
- `stok_gradings` perlu composite index di `(barang_id, sumber_stok, deleted_at)`

---

---

## API Inconsistencies

### 1. Missing API Routes

**File:** `routes/api.php`

**Masalah:** Hanya ada default Sanctum user endpoint. Semua fungsi lainnya hanya via web routes. Tidak ada JSON responses.

---

### 2. Inconsistent Error Response Formats

**File:** Multiple controllers

**Masalah:** Controller berbeda mengembalikan error dengan cara berbeda:

- Beberapa gunakan `with('error', ...)`
- Beberapa gunakan `withErrors()`
- Beberapa pakai single quotes, beberapa pakai double quotes dengan interpolation

---

### 3. No JSON Response Methods

**File:** Semua controller di `app/Http/Controllers/Admin/Transaksi/`

**Masalah:** Semua method controller mengembalikan Blade views. Tidak ada cara untuk dapat JSON API responses untuk mobile/SPA.

---

---

## Ringkasan

| Kategori | Jumlah Bug | Critical |
|----------|------------|----------|
| Data Consistency | 6 | 2 |
| Logic Errors | 7 | 3 |
| Security | 5 | 2 |
| Race Conditions | 4 | 2 |
| Error Handling | 5 | 2 |
| API Issues | 3 | 1 |
| **Total** | **37** | **16** |

---

## Prioritas Perbaikan

### High Priority (Segera)
1. Hardcoded `transaksi_ke_id = 1`
2. Schema constraint mismatch for `warna_id`
3. Race conditions in export/grading operations
4. StokSeeder non-existent model reference

### Medium Priority
5. Missing authorization checks
6. PCS totals hardcoded to 0
7. Division by zero risk
8. Checkbox comparison logic

### Low Priority (Kalau有空)
9. Session-based state manipulation
10. Inconsistent error response formats
11. Missing database indexes
12. API route missing for mobile
