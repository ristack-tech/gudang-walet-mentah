# Laporan Gabungan: Audit Keamanan & Analisis Bug
# Gudang Walet Mentah

> Dibuat: 2026-04-26
> Sumber: SECURITY_AUDIT_REPORT.md + BUG_ANALYSIS.md

---

## Ringkasan Eksekutif

| Sumber | Jumlah Temuan | Critical/High |
|--------|---------------|---------------|
| Security Audit | 10 temuan | 5 (CRITICAL + HIGH) |
| Bug Analysis | 37 bug | 16 critical |
| **Total** | **47 temuan** | **21 critical/high** |

---

## Daftar Isi

1. [Ruang Lingkup Audit](#ruang-lingkup-audit)
2. [Temuan Security Audit](#temuan-security-audit)
   - [CRITICAL](#critical-security)
   - [HIGH](#high-security)
   - [MEDIUM](#medium-security)
   - [LOW](#low-security)
3. [Temuan Bug Analysis](#temuan-bug-analysis)
   - [Critical Bugs](#critical-bugs)
   - [Logic Errors](#logic-errors)
   - [Security Issues](#security-issues)
   - [Race Conditions](#race-conditions)
   - [Error Handling](#error-handling)
   - [Data Consistency](#data-consistency)
   - [API Inconsistencies](#api-inconsistencies)
4. [Prioritas Perbaikan Gabungan](#prioritas-perbaikan-gabungan)

---

## Ruang Lingkup Audit

**File yang diaudit (Security Audit):**
- `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
- `app/Http/Controllers/Admin/Transaksi/StokBarangJadi/StokBarangJadiController.php`
- `app/Services/Admin/Transaksi/Export/ExportService.php`
- `app/Http/Controllers/Admin/Transaksi/Export/ExportController.php`
- Model terkait: `Export`, `ExportDetail`, `StokBarangJadi`, `StokBarangJadiDetail`

**Cakupan Bug Analysis:**
- Seluruh direktori `app/Services/Admin/Transaksi/`
- Seluruh direktori `app/Http/Controllers/Admin/Transaksi/`
- `database/migrations/`, `database/seeders/`
- Model dan route terkait

---

## Temuan Security Audit

### Ringkasan Severity

| Severity | Jumlah |
|----------|--------|
| CRITICAL | 2 |
| HIGH | 3 |
| MEDIUM | 3 |
| LOW | 2 |

---

### CRITICAL (Security) {#critical-security}

#### SEC-1. Race Condition pada Validasi Stok Export

**Lokasi:** `ExportController.php:58-82`

```php
$availableStocks = $this->stokBarangJadiService->getAvailableClopStocks();
if (!isset($availableStocks[$stokId])) {
    return redirect()->back()->withInput()->with('error', "Stok untuk barang...");
}
```

**Masalah:** Validasi stok dilakukan di controller, namun eksekusi `storeExport` terjadi di service. Antara kedua tahap tersebut (TOCTOU), request lain bisa mengosongkan stok yang sama.

**Dampak:** Admin bisa *double-spend* — mengekspor barang yang sama dua kali karena kedua request membaca stok yang sama sebelum ada yang menguranginya.

**Reproduksi:**
1. Buka dua tab browser
2. Step 1 & Step 2 di Tab A dan Tab B dengan item yang sama
3. Submit Tab A → berhasil
4. Submit Tab B → masih terlihat tersedia karena belum ada pengurangan di DB

**Mitigasi:** Gunakan `SELECT ... FOR UPDATE` atau `DB::lockForUpdate()` sebelum membaca stok.

---

#### SEC-2. Mass Assignment pada Detail Items

**Lokasi:** `StokBarangJadiService.php:36-46`

```php
foreach ($data['items'] as $item) {
    StokBarangJadiDetail::create([
        'stok_barang_jadi_id' => $stok->id,
        'nama_barang' => $item['nama_barang'],
        'jumlah_pcs' => $item['jumlah_pcs'] ?? 0,
        'berat' => $item['berat'] ?? 0,
    ]);
}
```

**Masalah:** Tidak ada validasi bahwa `$item` hanya berisi key yang diizinkan. Jika `$fillable` diperluas di masa depan, data berbahaya bisa tersimpan.

**Mitigasi:**
```php
$allowedKeys = ['nama_barang', 'jumlah_pcs', 'berat'];
foreach ($data['items'] as $item) {
    $filtered = array_intersect_key($item, array_flip($allowedKeys));
    StokBarangJadiDetail::create(array_merge($filtered, ['stok_barang_jadi_id' => $stok->id]));
}
```

---

### HIGH (Security) {#high-security}

#### SEC-3. Division by Zero pada Transfer ke Clop

**Lokasi:** `StokBarangJadiService.php:93-97`

```php
if ($tipeBox > 1) {
    $jumlahBox = floor($berat / $tipeBox);
}
```

**Masalah:** Jika `$tipeBox` bernilai 0 atau negatif, terjadi division by zero. Tidak ada validasi rentang nilai.

**Dampak:** Server error 500 atau perilaku matematika tidak terduga.

**Mitigasi:**
```php
if ($tipeBox <= 0) {
    throw new \InvalidArgumentException('Tipe box harus bernilai positif');
}
$jumlahBox = floor($berat / $tipeBox);
```

---

#### SEC-4. IDOR pada Transfer Stok Global

**Lokasi:** `StokBarangJadiService.php:55-108`

```php
public function transferToClop($stokGradingDetailId, $tipeBox, $berat)
{
    $stokGradingDetail = \App\Models\StokGradingDetail::with('stokGrading')->findOrFail($stokGradingDetailId);
```

**Masalah:** `findOrFail` hanya memvalidasi bahwa record ada, tanpa memeriksa:
- Apakah stok ini benar-benar milik lokasi yang bisa ditransfer
- Apakah admin login punya hak akses ke gudang/lokasi terkait
- Apakah stok sudah diproses/ditransfer sebelumnya (duplikat)

**Dampak:** Admin bisa mentransfer stok dari record yang tidak seharusnya bisa diakses.

**Mitigasi:**
```php
$stokGradingDetail = StokGradingDetail::where('id', $stokGradingDetailId)
    ->where('berat', '>', 0)
    ->firstOrFail();
$this->authorizeAccess($stokGradingDetail);
```

---

#### SEC-5. Race Condition pada Update Stok

**Lokasi:** `StokBarangJadiService.php:154-178`

```php
if ($deltaBerat > 0) {
    if ($stokGradingDetail->berat < $deltaBerat) {
        throw new \Exception("Stok Global tidak mencukupi...");
    }
    $stokGradingDetail->update(['berat' => $stokGradingDetail->berat - $deltaBerat]);
}
```

**Masalah:** Antara pembacaan `berat` dan update, request lain bisa mengubah nilai tersebut. Tidak ada database locking.

**Dampak:** Phantom stock — dua request concurrent bisa sama-sama berhasil mengurangi stok, menyebabkan stok negatif.

---

### MEDIUM (Security) {#medium-security}

#### SEC-6. Batch Number Predictability

**Lokasi:**
- `StokBarangJadiService.php:30` — `'batch' => $data['batch'] ?? 'SBJ-' . time()`
- `StokBarangJadiService.php:80` — `'batch' => 'TRF-CLOP-' . date('YmdHis')`
- `ExportService.php:29` — `batch = 'EXP-' . date('Ymd-His')`

**Masalah:** Batch number menggunakan timestamp yang bisa ditebak.

**Mitigasi:**
```php
'batch' => 'SBJ-' . Str::random(12) . '-' . time(),
```

---

#### SEC-7. Insufficient Input Validation pada Berat

**Lokasi:** `StokBarangJadiController.php:51`

```php
'berat' => 'required|numeric|min:' . ($tipeBox == 475 ? 475 : 500),
```

**Masalah:** Tidak ada validasi maximum — nilai ekstrem seperti 999999 gram bisa tersimpan tanpa validasi range wajar.

---

#### SEC-8. Regex DoS (Catastrophic Backtracking)

**Lokasi:** `StokBarangJadiService.php:133`

```php
if (preg_match('/No Kartu: (.*) \/ (.*)$/', $stok->keterangan, $matches)) {
```

**Masalah:** Pattern `(.*)` greedy bisa menyebabkan catastrophic backtracking pada input string panjang.

**Mitigasi:**
```php
if (preg_match('/No Kartu: ([^\/]+) \/ ([^$]+)$/', $stok->keterangan, $matches)) {
```

---

### LOW (Security) {#low-security}

#### SEC-9. Missing Rate Limiting

**Lokasi:** Semua endpoint

**Masalah:** Tidak ada rate limiting pada endpoint export dan stok barang jadi. Memungkinkan brute force attack pada race condition untuk double-spend.

**Mitigasi:** Tambahkan middleware `throttle` pada route.

---

#### SEC-10. Soft Delete Tidak Ada di StokBarangJadiDetail

**Lokasi:** `StokBarangJadiDetail.php:9`

**Masalah:** Model tidak menggunakan `SoftDeletes`. Hard delete menghilangkan audit trail.

---

## Temuan Bug Analysis

### Ringkasan Bug Analysis

| Kategori | Jumlah Bug | Critical |
|----------|------------|----------|
| Critical Bugs | 6 | 6 |
| Logic Errors | 7 | 3 |
| Security Issues | 5 | 2 |
| Race Conditions | 4 | 2 |
| Error Handling | 5 | 2 |
| Data Consistency | 6 | 2 |
| API Issues | 3 | 1 |
| **Total** | **37** | **16** |

---

### Critical Bugs

#### BUG-01. Hardcoded Lokasi ID — BarangMasukDemakService

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukDemakService.php:40`

```php
'transaksi_ke_id' => 1, // Central Warehouse usually
```

**Masalah:** `lokasi_id` di-hardcode = 1. Jika Lokasi ID 1 dihapus atau dipindahkan, akan menyebabkan foreign key constraint violation atau referensi lokasi yang salah.

**Perbaikan:** Ambil lokasi ID secara dinamis berdasarkan konfigurasi perusahaan atau pengaturan gudang default.

---

#### BUG-02. Schema Mismatch — warna_id di barang_keluar_demak

**File:** `database/migrations/2026_02_28_133049_create_barang_keluar_demak_table.php:17-18`

**Masalah:** Kolom `warna_id` ditandai NOT NULL di migration, tapi `BarangKeluarDemakService::store()` (baris 28) mengembalikan nullable:

```php
'warna_id' => $item['warna_id'] ?? null,
```

Ketika `warna_id` tidak disediakan, ini melanggar constraint NOT NULL.

**Perbaikan:** Buat `warna_id` nullable di migration ATAU validasi wajib di controller.

---

#### BUG-03. Race Condition — Export Bersamaan

**File:** `app/Http/Controllers/Admin/Transaksi/Export/ExportController.php:46-49`

**Masalah:** Dua user bisa export stok clop yang sama secara bersamaan:
1. User A load halaman, lihat 100g tersedia
2. User B load halaman, lihat 100g tersedia
3. User A export 100g — berhasil
4. User B export 100g — berhasil (stok jadi negatif)

**Perbaikan:** Gunakan `SELECT FOR UPDATE` atau database transaction dengan row-level locking pada `stok_barang_jadi_details`.

---

#### BUG-04. StokSeeder Referensi Model yang Tidak Ada

**File:** `database/seeders/StokSeeder.php:18`

```php
Stok::truncate();
```

**Masalah:** Seeder mereferensikan model `Stok` yang tidak ada di direktori Models. Akan menyebabkan fatal error jika seeder dijalankan.

**Perbaikan:** Ubah `Stok` ke nama model yang benar (kemungkinan `StokGudang`).

---

#### BUG-05. Silent Failure — StokGradingService::deleteGrading

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:165-167`

```php
if ($totalSisaBerat == 0) {
    return true;
}
```

**Masalah:** Ketika `$totalSisaBerat == 0`, fungsi mengembalikan `true` tapi TIDAK menghapus apapun. User melihat pesan sukses tapi record tetap ada.

**Perbaikan:** Hapus record ketika berat 0, atau kembalikan status yang tepat.

---

#### BUG-06. TOCTOU Race Condition — StokBarangJadiService

**File:** `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php:157-165`

```php
if ($stokGradingDetail->berat < $deltaBerat) {
    throw new \Exception("Stok Global tidak mencukupi...");
}
$stokGradingDetail->update([
    'berat' => $stokGradingDetail->berat - $deltaBerat,
]);
```

**Masalah:** Request lain bisa mengubah detail antara cek (baris 158) dan update (baris 163). *(Lihat juga SEC-5)*

---

### Logic Errors

#### BUG-07. PCS Totals di-Hardcode ke 0

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:59,62,65`

```php
'total_pcs_lempeng_bersih' => 0,
'total_pcs_lempeng_kotor' => 0,
'total_pcs_mangkok' => 0,
```

**Masalah:** Total PCS di-hardcode ke 0, padahal `StokGradingDetail` menyimpan nilai `pcs` yang tidak pernah diagregasi ke total parent.

**Perbaikan:** Hitung dan agregasi PCS dari record `StokGradingDetail`.

---

#### BUG-08. Resiko Division by Zero

**File:** `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php:93`

```php
if ($tipeBox > 1) {
    $jumlahBox = floor($berat / $tipeBox);
}
```

**Masalah:** Jika `$tipeBox` = 0 (dari manipulasi form), menyebabkan division by zero. *(Lihat juga SEC-3)*

**Perbaikan:** Tambahkan validasi `tipeBox > 0`.

---

#### BUG-09. Perhitungan Susut (Shrinkage) Negatif

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukJasaCuciService.php:32`

```php
$susut = $outItem->berat_per_pcs - $item['gram_diterima'];
```

**Masalah:** Jika `gram_diterima` > `berat_per_pcs`, `susut` menjadi negatif yang tidak masuk akal secara bisnis.

**Perbaikan:** Gunakan `max(0, ...)` atau validasi input.

---

#### BUG-10. Resiko Null Reference — transferToClop

**File:** `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php:86-87`

```php
$itemName = $stokGrading->namaBarang ? $stokGrading->namaBarang->nama_barang : 'Unknown Item';
```

**Masalah:** Jika relasi `namaBarang` null, fallback ke 'Unknown Item' tapi `barang_id` tetap null.

---

#### BUG-11. Loose Comparison Checkbox

**File:** `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukDemakController.php:102`

```php
return isset($item['is_checked']) && $item['is_checked'] == '1';
```

**Masalah:** HTML checkbox mengirim `on` saat dicentang, bukan `1`. Loose comparison `'on' == '1'` gagal.

**Perbaikan:** Gunakan `filter_var($item['is_checked'], FILTER_VALIDATE_BOOLEAN)`.

---

#### BUG-12. Missing Stok Gudang Update

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukDemakService.php:14-63`

**Masalah:** Ketika barang masuk demak dibuat, service membuat records tapi TIDAK mengupdate tabel `stok_gudangs`, menyebabkan inkonsistensi data.

---

#### BUG-13. Empty Items — StokBarangJadiService

**Lokasi:** `StokBarangJadiService.php` (store logic)

**Masalah:** Jika `$data['items']` kosong atau null, parent record tetap dibuat tanpa detail. StokBarangJadi tanpa details adalah record valid di database tapi useless secara bisnis.

**Fix:**
```php
if (empty($data['items'])) {
    throw new \InvalidArgumentException('Items tidak boleh kosong. Minimal 1 item diperlukan.');
}
```

---

### Security Issues (dari Bug Analysis)

#### BUG-14. Missing Authorization — Semua Controller

**File:** Semua controller di `app/Http/Controllers/Admin/Transaksi/`

**Masalah:** Tidak ada authorization checks. User yang terautentikasi bisa:
- Lihat data company siapapun
- Modify record siapapun
- Hapus batch siapapun

Hanya middleware `auth` yang digunakan, yang hanya memeriksa apakah user sudah login.

---

#### BUG-15. Session-based State Manipulation

**File:** `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukDemakController.php:52`

```php
session(['barang_masuk_demak_step1' => $validated]);
```

**Masalah:** Multi-step wizard forms menyimpan data di session tanpa validasi signature kriptografis. Attacker bisa memodifikasi session data.

---

#### BUG-16. Tidak Ada API Routes untuk Mobile/External

**File:** `routes/api.php:17-19`

**Masalah:** File API routes hanya berisi default Sanctum user endpoint. Tidak ada JSON API responses.

---

#### BUG-17. Mass Assignment Protection — StokBarangJadiDetail

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

**Masalah:** `nama_barang` menerima teks arbitrary tanpa validasi bahwa itu match dengan barang actual.

---

#### BUG-18. Export Tidak Mengurangi Stok Clop (Logical Bug)

**Lokasi:** `ExportService.php:25-49`

**Masalah:** Export HANYA membuat record di `export_details`. Tidak ada pengurangan stok di `stok_barang_jadi_details`. Stok clop "available" di report tidak mencerminkan kenyataan.

**Verifikasi:** Cek apakah ada trigger atau observer lain yang memodifikasi `stok_barang_jadi_details`.

---

### Race Conditions (dari Bug Analysis)

#### BUG-19. Concurrent Export — ExportController

**File:** `app/Http/Controllers/Admin/Transaksi/Export/ExportController.php:36-59`

*(Lihat BUG-03 dan SEC-1)* — Tidak ada database-level locking saat cek/export stock.

---

#### BUG-20. Concurrent Stok Grading

**File:** `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php:14-88`

**Masalah:** Fungsi `storeGrading` melakukan cek via `getGlobalSummaryForBarang()` tapi antara cek dan create, grading lain bisa melewati validasi yang sama.

---

#### BUG-21. No Locking in StokGudangService

**File:** `app/Services/StokGudang/StokGudangService.php:15-46, 198-253`

**Masalah:** `getGlobalSummaryForBarang()`, `getDemakSummary()` melakukan agregasi tanpa locking. Digunakan untuk validasi stock sebelum write tapi state bisa berubah.

---

#### BUG-22. Delete-Then-Insert Without Lock

**File:** `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukPembelianSupplierService.php:38-61`

```php
$barangMasukBatch->barangMasukSupplier()->delete(); // Baris 46
$barangMasukSupplier::create([...]); // Baris 50-57
```

**Masalah:** Jika insert gagal, child records hilang tanpa rollback.

---

#### BUG-23. Race Condition di getAvailableClopStocks (Phantom Read)

**Lokasi:** `StokBarangJadiService.php:236-273`

**Masalah:** Dua query terpisah tanpa lock — antara pembacaan `$clopStocks` dan `$exportStocks`, data lain bisa berubah.

**Fix:** Gunakan transaction dengan lock, atau query tunggal dengan LEFT JOIN dan COALESCE.

---

#### BUG-24. Export Delete Tidak Restore Stok

**Lokasi:** `ExportService.php:52-62`

```php
public function delete($id)
{
    return DB::transaction(function () use ($id) {
        $export = Export::findOrFail($id);
        $export->exportDetails()->delete();
        return $export->delete();
    });
}
```

**Masalah:** Void export tidak mengembalikan stok clop, menyebabkan discrepancy antara sistem dan actual stock.

---

### Error Handling

#### BUG-25. Silent Failure — deleteGrading

*(Lihat BUG-05)* — Sama seperti Critical Bug #5, return `true` tanpa menghapus apapun.

---

#### BUG-26. Swallowed Exception — BarangKeluarDemakController

**File:** `app/Http/Controllers/Admin/Transaksi/barangKeluar/BarangKeluarDemakController.php:174-180`

```php
catch (\Exception $e) {
    return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
```

**Masalah:** Tidak ada logging, tidak ada pembedaan antar tipe error, raw error message bisa leak system info.

---

#### BUG-27. Unhandled ModelNotFoundException — ExportService

**File:** `app/Services/Admin/Transaksi/Export/ExportService.php:34-38`

```php
$export = ExportBarang::findOrFail($id);
return $export->delete();
```

**Masalah:** `findOrFail` melempar unhandled exception jika record tidak ada.

---

#### BUG-28. Empty Array Check Causes Data Loss

**File:** `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukPembelianSupplierController.php:123`

```php
if (!$step1Data || empty($items)) {
    return redirect()->route(...);
}
```

**Masalah:** Saat redirect, user kehilangan session data step1 dan harus input ulang semua.

---

#### BUG-29. Update Stok Tidak Ajek saat Concurrent Edit

**Lokasi:** `StokBarangJadiService.php:146-185`

**Masalah:**
1. Penggunaan `first()` alih-alih `findOrFail()` — jika tidak ketemu, update tidak jalan secara silent
2. Tidak ada database lock pada `$stokGradingDetail` antara baca dan update
3. Tidak ada optimistic locking (version check)

**Dampak:** Concurrent updates bisa saling overwrite, causing lost updates.

---

#### BUG-30. Missing Null Check pada Delete Restore Logic

**Lokasi:** `StokBarangJadiService.php:215-226`

```php
if ($detail) {
    $totalBeratTransfer = $stok->details->sum('berat');
    $detail->update(['berat' => $detail->berat + $totalBeratTransfer]);
}
```

**Masalah:** Jika `$stok->details` adalah lazy-loaded relationship dan tidak di-eager load, restore bisa tidak jalan sempurna.

---

### Data Consistency

#### BUG-31. Missing barang_id Foreign Key

**File:** `database/migrations/2026_02_28_133049_create_barang_keluar_demak_table.php`

**Masalah:** Tabel `barang_keluar_demak` punya `partai_id` tapi TIDAK punya foreign key untuk `barang_id`. Item yang keluar ke Demak tidak melacak barang mana.

---

#### BUG-32. Inconsistent Column Naming

**File:** `database/migrations/2026_02_28_134152_create_barang_keluar_jasa_cuci_table.php`

**Masalah:** Menggunakan `nama_barang_id` sebagai foreign key, tapi tabel lain seperti `barang_masuk_jasa_cuci` gunakan `barang_id`.

---

#### BUG-33. Orphaned Records — BarangKeluarJasaCuciService

**File:** `app/Services/Admin/Transaksi/barangKeluar/BarangKeluarJasaCuiService.php:28`

**Masalah:** Jika item berbasis session dimanipulasi, `kode_bahan_id` bisa null, menciptakan orphaned records.

---

#### BUG-34. Missing Indexes for Performance

**File:** `app/Services/StokGudang/StokGudangService.php`

**Masalah:** Query agregasi kompleks tanpa indexes:
- `barang_masuk_demak` perlu index di `barang_id`
- `barang_masuk_jasa_cuci` perlu index di `barang_id`
- `stok_gradings` perlu composite index di `(barang_id, sumber_stok, deleted_at)`

---

#### BUG-35. Fragile Regex Parsing di Keterangan

**Lokasi:** `StokBarangJadiService.php:133` dan `StokBarangJadiService.php:205`

```php
if (preg_match('/No Kartu: (.*) \/ (.*)$/', $stok->keterangan, $matches)) {
```

**Masalah:**
1. Greedy `(.*)` bisa cause backtracking pada input panjang
2. Tidak ada handling fallback jika format tidak sesuai
3. Jika keterangan null, `preg_match` akan error

**Fix:**
```php
$keterangan = $stok->keterangan ?? '';
if (preg_match('/No Kartu:\s*([^\/]+)\s*\/\s*([^\s]+)\s*$/', $keterangan, $matches)) {
    $noKartu = trim($matches[1]);
    $grade = trim($matches[2]);
} else {
    throw new \RuntimeException('Format keterangan tidak valid untuk parsing No Kartu');
}
```

---

#### BUG-36. Batch Number Collision Risk

**Lokasi:**
- `StokBarangJadiService.php:30` — `'batch' => 'SBJ-' . time()`
- `StokBarangJadiService.php:80` — `'batch' => 'TRF-CLOP-' . date('YmdHis')`

**Masalah:** `time()` dan `date('YmdHis')` return value dalam detik. Double-click atau concurrent request dalam detik yang sama akan menghasilkan batch number duplikat. *(Lihat juga SEC-6)*

**Fix:**
```php
'batch' => 'SBJ-' . Str::random(8) . '-' . time(),
```

---

### API Inconsistencies

#### BUG-37. Missing API Routes

**File:** `routes/api.php`

**Masalah:** Hanya ada default Sanctum user endpoint. Tidak ada JSON responses untuk mobile/SPA.

---

#### BUG-38. Inconsistent Error Response Formats

**File:** Multiple controllers

**Masalah:** Controller berbeda mengembalikan error dengan cara berbeda:
- Beberapa gunakan `with('error', ...)`
- Beberapa gunakan `withErrors()`
- Inkonsistensi format string (single vs double quotes)

---

#### BUG-39. No JSON Response Methods

**File:** Semua controller di `app/Http/Controllers/Admin/Transaksi/`

**Masalah:** Semua method controller mengembalikan Blade views. Tidak ada cara untuk mendapatkan JSON API responses.

---

## Prioritas Perbaikan Gabungan

### 🔴 Segera (Critical)

| # | Item | Referensi |
|---|------|-----------|
| 1 | Database-level locking (`DB::lockForUpdate()`) pada semua operasi baca-tulis stok | SEC-1, SEC-5, BUG-03, BUG-06, BUG-19, BUG-21, BUG-23 |
| 2 | Hardcoded `transaksi_ke_id = 1` — ambil dinamis dari konfigurasi | BUG-01 |
| 3 | Schema constraint mismatch untuk `warna_id` | BUG-02 |
| 4 | StokSeeder mereferensikan model yang tidak ada (`Stok` → `StokGudang`) | BUG-04 |
| 5 | Silent failure di `deleteGrading` — fix return value atau hapus record | BUG-05 |
| 6 | Validasi struktur `$data['items']` sebelum iterasi (mass assignment) | SEC-2, BUG-17 |

### 🟠 Tinggi (High)

| # | Item | Referensi |
|---|------|-----------|
| 7 | Implementasi authorization check untuk setiap transfer/ubah/hapus stok | BUG-14, SEC-4 |
| 8 | Ganti batch number generation dengan UUID / Str::random | SEC-6, BUG-36 |
| 9 | Verifikasi BUG-18 — apakah export benar-benar mengurangi stok clop | BUG-18, BUG-24 |
| 10 | PCS totals tidak boleh hardcode ke 0 — agregasi dari detail | BUG-07 |
| 11 | Validasi division by zero untuk `tipeBox` | BUG-08, SEC-3 |
| 12 | Fix checkbox comparison (`'on' == '1'` → `FILTER_VALIDATE_BOOLEAN`) | BUG-11 |

### 🟡 Medium

| # | Item | Referensi |
|---|------|-----------|
| 13 | Tambahkan rate limiting pada semua endpoint Transaksi | SEC-9 |
| 14 | Fix regex pattern dengan non-greedy quantifier + null-safe check | SEC-8, BUG-35 |
| 15 | Validasi input maximum untuk berat | SEC-7 |
| 16 | Fix shrinkage calculation agar tidak negatif | BUG-09 |
| 17 | Tambahkan error logging pada catch blocks | BUG-26 |
| 18 | Handle `ModelNotFoundException` di ExportService | BUG-27 |

### 🟢 Low (Kalau Ada Waktu)

| # | Item | Referensi |
|---|------|-----------|
| 19 | Konsistensi penggunaan `SoftDeletes` di semua model | SEC-10 |
| 20 | Tambahkan database indexes untuk performa query agregasi | BUG-34 |
| 21 | Session-based state manipulation — tambahkan signature kriptografis | BUG-15 |
| 22 | Konsistensi error response format di semua controller | BUG-38 |
| 23 | Perbaiki penamaan kolom — `nama_barang_id` vs `barang_id` | BUG-32 |
| 24 | Tambahkan API routes untuk mobile/external | BUG-16, BUG-37, BUG-39 |
| 25 | Null-safe checks pada delete restore logic | BUG-30 |

---

*Laporan ini merupakan gabungan dari:*
- *`SECURITY_AUDIT_REPORT.md` — Auditor: Claude Code Security Review (2026-04-25)*
- *`BUG_ANALYSIS.md` — Dibuat: 2026-04-26*