@extends('layouts.dashboard')

@section('title', 'Profil - MARTIP')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Profil Pengguna</h1>
        <p class="text-gray-500 text-sm">Kelola data diri dan keamanan akun Anda.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
        <div class="flex items-center gap-6 mb-8 pb-8 border-b border-gray-100">
            <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-4xl font-bold border-4 border-white shadow-md">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                <div class="mt-2 inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-lg border border-green-100">
                    <i class='bx bx-check-shield'></i> Akun Terverifikasi
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl text-sm flex items-center gap-2">
                <i class='bx bx-check-circle text-lg'></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.profil.update') }}" method="POST">
            @csrf
            @method('PUT')
            <h3 class="text-lg font-bold text-gray-900 mb-6">Informasi Personal</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm bg-gray-50" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm bg-gray-50" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP (OnoPay)</label>
                    <input type="text" name="phone" value="{{ Auth::user()->phone }}" placeholder="Contoh: 081234567890" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm" required>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="reset" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition shadow-md shadow-blue-200 text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
