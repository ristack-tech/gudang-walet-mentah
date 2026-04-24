# Dokumentasi Perubahan Modul Export - UI Berbasis Kartu (Box)

Dokumen ini menjelaskan perubahan pada modul Export untuk mengimplementasikan sistem pemilihan berbasis kartu (grid) dan peningkatan integrasi data stok barang jadi.

## 1. Perubahan Backend

### `app/Http/Controllers/Admin/Transaksi/Export/ExportController.php`
- **Validasi Ketat**: 
    - Menambahkan validasi `items.*.stok_id` untuk memastikan barang yang dipilih benar-benar ada di database.
    - Menambahkan pengecekan ketersediaan stok fisik tepat sebelum penyimpanan untuk menghindari *double export* jika ada dua user yang mengakses secara bersamaan.
- **Logika Penyimpanan**: Mengubah pemrosesan input dari array biasa menjadi array asosiatif untuk akurasi data box yang lebih tinggi.

### `app/Services/Admin/Transaksi/Export/ExportService.php`
- **`storeExport`**: Mengotomatisasi pengisian kolom `berat`, `box`, dan `pcs` pada tabel `export_details` berdasarkan data asli dari stok barang jadi yang dipilih.

### `app/Services/Admin/Transaksi/StokBarangJadi/StokBarangJadiService.php`
- **Integrasi Stok Clop**: Logika "Transfer Global ke Clop" diintegrasikan agar data yang masuk ke tabel stok barang jadi memiliki informasi jumlah box yang akurat, yang nantinya direpresentasikan sebagai kartu yang dapat dipilih di modul Export.

## 2. Perubahan Frontend (UI/UX)

### `resources/views/admin/export/create-step-2.blade.php`
- **Grid Kartu Mikro**: Tampilan daftar barang diubah menjadi grid kartu kecil (Full Width) untuk mempercepat visualisasi stok.
- **Pilih Kartu**: Pengguna dapat memilih box (kartu) secara langsung dengan klik, yang akan mengaktifkan kolom input harga jual.
- **Input Harga Terintegrasi**: Kolom input harga muncul langsung di dalam kartu segera setelah kartu diklik/dipilih.
- **Sticky Action Bar**: Menampilkan ringkasan (Total Berat & Total Box) secara *real-time* di bagian bawah layar tanpa mengganggu navigasi katalog.
- **Fitur Cepat**: Penambahan fitur "Pilih Semua Terlihat" untuk efisiensi saat melakukan pencarian grade tertentu.

---
**Tanggal**: 24 April 2026
**Proyek**: Gudang Walet Mentah
