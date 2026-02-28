@extends('layouts.app')

@section('title', 'Tambah Kode Bahan')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <a href="{{ route('kode-bahan.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-800">Tambah Kode Bahan Baru</h1>
                </div>
                <p class="text-sm text-gray-600 ml-7">Isi form di bawah untuk menambahkan kode bahan baru</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <form action="{{ route('kode-bahan.store') }}" method="POST" class="p-6">
                    @csrf

                    <!-- Kode Bahan -->
                    <div class="mb-6">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Bahan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                            placeholder="Masukkan kode bahan" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('kode-bahan.index') }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Kode Bahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus pada input pertama
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('nama')?.focus();
        });

        // Form validation sebelum submit
        document.querySelector('form')?.addEventListener('submit', function (e) {
            const name = document.getElementById('nama').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Kode bahan wajib diisi!');
                document.getElementById('nama').focus();
                return false;
            }

            // Konfirmasi sebelum submit
            if (!confirm('Apakah Anda yakin ingin menyimpan kode bahan ini?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
