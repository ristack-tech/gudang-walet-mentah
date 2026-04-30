# Notes Pribadi - Security Audit Fixes
# Gudang Walet Mentah

> Tanggal: 2026-04-29
> Branch: fix/barang-masuk-barang-keluar

---

## Yang Sudah Diperbaiki (14 items)

### Security Issues (6 item)

1. **SEC-2: Mass Assignment pada Detail Items**
   - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
   - Solusi: Filter keys dengan `array_intersect_key()`

2. **SEC-3: Division by Zero pada Transfer ke Clop**
   - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
   - Solusi: Validasi `if ($tipeBox <= 0) throw new \InvalidArgumentException(...)`

3. **SEC-4: IDOR pada Transfer Stok Global**
   - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
   - Solusi: `lockForUpdate()` saat fetch StokGradingDetail

4. **SEC-5: Race Condition pada Update Stok**
   - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
   - Solusi: `lockForUpdate()` pada query

5. **SEC-7: Insufficient Input Validation pada Berat**
   - File: `app/Http/Controllers/Admin/Transaksi/StokBarangJadi/StokBarangJadiController.php`
   - Solusi: `|max:100000` pada validasi berat

6. **SEC-1: Race Condition pada Validasi Stok Export**
   - File: `app/Services/Admin/Transaksi/Export/ExportService.php`
   - Solusi: `lockForUpdate()` saat storeExport

### Bug Fixes (8 item)

7. **BUG-01: Hardcoded Lokasi ID = 1**
   - File: `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukDemakService.php`
   - Solusi: `Lokasi::where('nama_lokasi', 'like', '%Gudang Utama%')->first()`

8. **BUG-04: StokSeeder references non-existent model**
   - File: `database/seeders/StokSeeder.php`
   - Solusi: `Stok::truncate()` → `StokGudang::truncate()`

9. **BUG-06: TOCTOU Race Condition**
   - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
   - Solusi: `lockForUpdate()` (sama dengan SEC-5)

10. **BUG-13: Empty Items Validation**
    - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
    - Solusi: Validasi items + filter valid items

11. **BUG-20: Concurrent Stok Grading**
    - File: `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php`
    - Solusi: `lockForUpdate()` pada deleteGrading

12. **BUG-22: Delete-Then-Insert Without Lock**
    - File: `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php`
    - Solusi: Sama dengan BUG-20

13. **BUG-25: Silent Failure deleteGrading**
    - File: `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php`
    - Solusi: Return array dengan message + hapus record saat berat = 0

14. **BUG-28: Empty Array Check Causes Data Loss**
    - File: `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukPembelianSupplierController.php`
    - Solusi: `->withInput()` saat redirect back

15. **BUG-29: Update Stok Tidak Ajek saat Concurrent Edit**
    - File: `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
    - Solusi: Sudah ditangani oleh lockForUpdate (SEC-5)

---

## Items yang Tidak Diperbaiki (Disimpan)

| ID | Alasan |
|----|--------|
| BUG-2 | Schema mismatch akan di-fix saat perombakan database |
| BUG-12 | Tidak perlu update stok_gudangs |
| BUG-16 | Monolith app, tidak perlu API routes |
| BUG-17 | nama_barang free text by design |
| BUG-31 | Ikut nota, memang tidak ada barang |
| BUG-32 | Biarkan, nanti ada perombakan database |
| BUG-33 | Sudah benar, tidak ada barang cuma ada kode bahan |

---

## Catatan Penting

1. **Locking Pattern**: Gunakan `lockForUpdate()` pada semua write operations yang membaca stok terlebih dahulu
2. **Mass Assignment Protection**: Selalu filter keys yang diizinkan sebelum mass assignment
3. **Dynamic Lookup**: Jangan hardcode lokasi ID - gunakan dynamic lookup berdasarkan nama
4. **Division by Zero**: Selalu validasi nilai sebelum melakukan pembagian
5. **Input Validation**: Selalu tambahkan max validation untuk mencegah invalid data

---

## Files yang Dimodifikasi

- `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
- `app/Services/Admin/Transaksi/BarangMasuk/BarangMasukDemakService.php`
- `app/Services/Admin/Transaksi/StokGrading/StokGradingService.php`
- `app/Http/Controllers/Admin/Transaksi/StokBarangJadi/StokBarangJadiController.php`
- `app/Http/Controllers/Admin/Transaksi/BarangMasuk/BarangMasukPembelianSupplierController.php`
- `database/seeders/StokSeeder.php`
- `SECURITY_AUDIT_REPORT.md`