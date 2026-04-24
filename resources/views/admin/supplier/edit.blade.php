@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <a href="{{ route('supplier.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Supplier</h1>
                </div>
                <p class="text-sm text-gray-600 ml-7">Perbarui informasi supplier</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Supplier -->
                    <div class="mb-6">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Supplier <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama"
                            value="{{ old('nama', $supplier->nama) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                            placeholder="Masukkan nama supplier" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                            placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $supplier->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Update -->
                    @if($supplier->updated_at)
                        <div class="mb-6 bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Terakhir diperbarui: {{ $supplier->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('supplier.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
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
        document.querySelector('form[method="POST"]')?.addEventListener('submit', function (e) {
            const name = document.getElementById('nama').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Nama supplier wajib diisi!');
                document.getElementById('nama').focus();
                return false;
            }
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function (event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection
