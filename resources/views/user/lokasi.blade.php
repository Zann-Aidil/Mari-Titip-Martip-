@extends('layouts.dashboard')

@section('title', 'Lokasi Titip - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Cari Lokasi Titip</h1>
        <p class="text-gray-500 text-sm">Temukan lokasi mitra terbaik untuk menitipkan barang Anda.</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-8">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            <div class="relative w-full max-w-sm">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" placeholder="Cari nama lokasi atau alamat..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            </div>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none">
                <option>Semua Kategori</option>
                <option>Kafe</option>
                <option>Laundry</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-5 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition flex items-center gap-2">
                <i class='bx bx-map-alt'></i> Tampilkan Peta
            </button>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($locations as $lokasi)
        <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
            <div class="relative h-48 overflow-hidden">
                @if($lokasi->image)
                    <img src="{{ asset('storage/' . $lokasi->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                    <img src="{{ $lokasi->image_url }}" alt="Lokasi" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @endif
                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-blue-600 shadow-sm">
                    Tersedia
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition">{{ $lokasi->nama_lokasi }}</h3>
                        <p class="text-sm text-gray-500 flex items-center gap-1"><i class='bx bx-map'></i> {{ $lokasi->alamat }}</p>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Mulai dari</p>
                        <p class="text-lg font-black text-blue-600">Rp {{ number_format($lokasi->tarif_per_hari, 0, ',', '.') }}<span class="text-xs text-gray-500 font-normal">/hari</span></p>
                    </div>
                    <a href="{{ route('user.lokasi.detail', $lokasi->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-md shadow-blue-200">
                        Pilih Lokasi
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">Belum ada lokasi yang tersedia.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
