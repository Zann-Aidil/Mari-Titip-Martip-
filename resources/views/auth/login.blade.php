@extends('layouts.public')

@section('title', 'Login - MARTIP')

@section('content')
<section class="py-12 bg-white min-h-[calc(100vh-160px)] flex flex-col justify-center relative overflow-hidden">
    <!-- Background styling from mockup -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-24">
            
            <!-- Left Illustration -->
            <div class="hidden lg:block lg:w-1/2">
                <img src="https://img.freepik.com/free-vector/mobile-login-concept-illustration_114360-83.jpg" alt="Login Illustration" class="w-full max-w-md mx-auto mix-blend-multiply">
            </div>

            <!-- Right Form -->
            <div class="w-full lg:w-1/2 max-w-md mx-auto">
                <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-500 mb-8">Masuk ke akun MARTIP Anda</p>

                    @if(session('error'))
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm flex items-center gap-2">
                            <i class='bx bx-error-circle text-lg'></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email atau Nomor HP</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class='bx bx-user'></i>
                                </div>
                                <input type="email" name="email" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" placeholder="Masukkan email atau nomor HP Anda" value="{{ old('email') }}">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class='bx bx-lock-alt'></i>
                                </div>
                                <input type="password" name="password" required class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" placeholder="Masukkan password Anda">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 cursor-pointer hover:text-gray-600">
                                    <i class='bx bx-show'></i>
                                </div>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between mb-8">
                            <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2 w-4 h-4">
                                Ingat saya
                            </label>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lupa password?</a>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                            <i class='bx bxs-lock-open-alt'></i> Masuk
                        </button>
                    </form>

                    <!-- Social Login -->
                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">atau masuk dengan</span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <button class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 rounded-xl hover:bg-gray-50 transition text-sm font-medium text-gray-700">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google"> Google
                            </button>
                            <button class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 rounded-xl hover:bg-gray-50 transition text-sm font-medium text-gray-700">
                                <i class='bx bxl-apple text-xl'></i> Apple
                            </button>
                        </div>
                    </div>

                    <p class="text-center text-sm text-gray-600 mt-8">
                        Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">Daftar sekarang</a>
                    </p>
                </div>
            </div>
            
        </div>
        
        <!-- Bottom Features -->
        <div class="mt-20 border-t border-gray-100 pt-10 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><i class='bx bx-shield-quarter'></i></div>
                <div><p class="text-sm font-bold text-gray-800">Aman & Terpercaya</p><p class="text-xs text-gray-500">Sistem keamanan berlapis</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600"><i class='bx bx-stopwatch'></i></div>
                <div><p class="text-sm font-bold text-gray-800">Mudah & Praktis</p><p class="text-xs text-gray-500">Proses cepat dan efisien</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><i class='bx bx-target-lock'></i></div>
                <div><p class="text-sm font-bold text-gray-800">Real-time Tracking</p><p class="text-xs text-gray-500">Pantau titipan kapan saja</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><i class='bx bx-map-pin'></i></div>
                <div><p class="text-sm font-bold text-gray-800">Berbagai Lokasi</p><p class="text-xs text-gray-500">Tersebar di banyak kota</p></div>
            </div>
        </div>
    </div>
</section>
@endsection
