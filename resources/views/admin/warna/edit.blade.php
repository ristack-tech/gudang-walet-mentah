@extends('layouts.app')

@section('title', 'Edit Warna')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center mb-2">
                    <a href="{{ route('warna.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Data Warna</h1>
                </div>
                <p class="text-sm text-gray-600 ml-7">Perbarui informasi data warna</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <form action="{{ route('warna.update', $warna->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Kode Warna -->
                    <div class="mb-6">
                        <label for="kode_warna" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Warna <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_warna" id="kode_warna"
                            value="{{ old('kode_warna', $warna->kode_warna) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kode_warna') border-red-500 @enderror"
                            placeholder="Masukkan kode warna" required>
                        @error('kode_warna')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna -->
                    <div class="mb-6">
                        <label for="warna" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Warna <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="warna" id="warna"
                            value="{{ old('warna', $warna->warna) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('warna') border-red-500 @enderror"
                            placeholder="Masukkan nama warna" required>
                        @error('warna')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Update -->
                    @if($warna->updated_at)
                        <div class="mb-6 bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Terakhir diperbarui: {{ $warna->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <button type="button" onclick="confirmDelete()"
                            class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-md text-sm font-medium transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Data
                        </button>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('warna.index') }}"
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Data</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus <span
                        class="font-semibold text-gray-900">{{ $warna->warna }}</span>?
                </p>
                <p class="text-sm text-red-600 mt-1">Tindakan ini tidak dapat dibatalkan!</p>
                <form id="deleteForm" action="{{ route('warna.destroy', $warna->id) }}" method="POST"
                    class="mt-6">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3 justify-center">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm font-medium transition duration-200">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus pada input pertama
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('kode_warna')?.focus();
        });

        // Form validation sebelum submit
        document.querySelector('form[method="POST"]')?.addEventListener('submit', function (e) {
            const kode = document.getElementById('kode_warna').value.trim();
            const nama = document.getElementById('warna').value.trim();

            if (!kode || !nama) {
                e.preventDefault();
                alert('Kode dan Nama warna wajib diisi!');
                return false;
            }

            // Konfirmasi sebelum submit
            if (!confirm('Apakah Anda yakin ingin menyimpan perubahan ini?')) {
                e.preventDefault();
                return false;
            }
        });

        // Delete modal functions
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

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
