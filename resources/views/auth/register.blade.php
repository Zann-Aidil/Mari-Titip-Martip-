@extends('layouts.public')

@section('title', 'Register - MARTIP')

@section('content')
<section class="py-12 bg-white min-h-[calc(100vh-160px)] flex flex-col justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-24">
            
            <!-- Left Illustration -->
            <div class="hidden lg:block lg:w-1/2">
                <img src="https://img.freepik.com/free-vector/sign-up-concept-illustration_114360-7865.jpg" alt="Register Illustration" class="w-full max-w-md mx-auto mix-blend-multiply">
            </div>

            <!-- Right Form -->
            <div class="w-full lg:w-1/2 max-w-md mx-auto">
                <div class="bg-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                    <p class="text-gray-500 mb-8">Daftar untuk mulai menitipkan barang</p>

                    <form action="{{ url('/register') }}" method="POST">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class='bx bx-user'></i>
                                </div>
                                <input type="text" name="name" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}">
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class='bx bx-envelope'></i>
                                </div>
                                <input type="email" name="email" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" placeholder="Masukkan alamat email Anda" value="{{ old('email') }}">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class='bx bx-phone'></i>
                                </div>
                                <input type="text" name="phone" required class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" placeholder="Misal: 08123456789" value="{{ old('phone') }}">
                            </div>
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-5">
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

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class='bx bx-lock-alt'></i>
                                </div>
                                <input type="password" name="password_confirmation" required class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" placeholder="Konfirmasi password Anda">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 cursor-pointer hover:text-gray-600">
                                    <i class='bx bx-show'></i>
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-8">
                            <label class="flex items-start text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" required class="rounded border-gray-300 text-green-600 focus:ring-green-500 mr-2 mt-1 w-4 h-4">
                                <span>Saya setuju dengan <a href="#" class="font-medium text-blue-600 hover:text-blue-700">Syarat & Ketentuan</a> dan <a href="#" class="font-medium text-blue-600 hover:text-blue-700">Kebijakan Privasi</a></span>
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="w-full bg-green-600 text-white font-medium py-3 rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200 flex items-center justify-center gap-2">
                            <i class='bx bx-user-plus'></i> Daftar
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-600 mt-8">
                        Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk sekarang</a>
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
