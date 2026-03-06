@extends('layouts.guest')

@section('content')
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 lg:p-8 font-sans">
        <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

            <!-- Visual Side Panel -->
            <div class="md:w-1/2 bg-blue-700 relative hidden md:block">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/90 to-indigo-900/90 z-10"></div>
                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=1000"
                    alt="Warehouse" class="absolute inset-0 w-full h-full object-cover">

                <div class="relative z-20 h-full flex flex-col justify-center p-12 text-white">
                    <div class="mb-8">
                        <span
                            class="bg-blue-500/30 text-blue-100 px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase">
                            Premium System
                        </span>
                    </div>
                    <h1 class="text-5xl font-extrabold leading-tight mb-6">
                        Gudang <br />
                        <span class="text-blue-300">Walet Mentah</span>
                    </h1>
                    <p class="text-lg text-blue-100/80 leading-relaxed max-w-md">
                        Solusi manajemen inventaris sarang burung walet yang presisi, aman, dan terintegrasi secara
                        real-time.
                    </p>


                </div>
            </div>

            <!-- Form Side -->
            <div class="md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                    <p class="text-gray-500">Silakan masuk untuk mengelola stok gudang Anda.</p>
                </div>

                <form class="space-y-5" method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-sm font-semibold text-gray-700 ml-1">Alamat Email</label>
                        <div class="group relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all outline-none @error('email') border-red-500 bg-red-50 @enderror"
                                placeholder="nama@perusahaan.com" />
                        </div>
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 ml-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center px-1">
                            <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">Lupa
                                    Password?</a>
                            @endif
                        </div>
                        <div class="group relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all outline-none @error('password') border-red-500 bg-red-50 @enderror"
                                placeholder="••••••••" />
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                <svg id="eye-open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 ml-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center py-2">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Ingat sesi
                            saya</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun?
                        <a href="#" class="text-blue-600 font-bold hover:underline">Hubungi Admin</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const eyeOpenIcon = document.getElementById('eye-open');
                const eyeClosedIcon = document.getElementById('eye-closed');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpenIcon.classList.add('hidden');
                    eyeClosedIcon.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOpenIcon.classList.remove('hidden');
                    eyeClosedIcon.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection
```