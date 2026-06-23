@extends('layouts.public')

@section('title', 'Cari Lokasi Titip - MARTIP')

@section('content')
<!-- Header Search -->
<section class="bg-blue-600 pt-32 pb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl font-bold text-white mb-6">Temukan Lokasi Titip Terdekat</h1>
        <p class="text-blue-100 mb-10 max-w-2xl mx-auto">Tersedia lebih dari 120 lokasi mitra yang aman dan diawasi 24 jam untuk menitipkan barang Anda.</p>
        
        <div class="bg-white p-2 rounded-2xl shadow-xl flex flex-col md:flex-row items-center max-w-3xl mx-auto gap-2">
            <div class="flex-1 w-full flex items-center px-4 py-2 bg-gray-50 rounded-xl border border-transparent focus-within:border-blue-200 focus-within:bg-white transition">
                <i class='bx bx-search text-gray-400 text-xl'></i>
                <input type="text" placeholder="Cari nama lokasi atau kota..." class="w-full bg-transparent border-none focus:ring-0 px-3 text-gray-700 outline-none">
            </div>
            <div class="flex-1 w-full flex items-center px-4 py-2 bg-gray-50 rounded-xl border border-transparent focus-within:border-blue-200 focus-within:bg-white transition">
                <i class='bx bx-category text-gray-400 text-xl'></i>
                <select class="w-full bg-transparent border-none focus:ring-0 px-3 text-gray-700 outline-none">
                    <option value="">Semua Kategori</option>
                    <option value="kafe">Kafe</option>
                    <option value="laundry">Laundry</option>
                    <option value="indekos">Indekos</option>
                    <option value="toko">Toko</option>
                </select>
            </div>
            <button class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-medium transition shadow-md shadow-blue-200">
                Cari
            </button>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold text-gray-900">Hasil Pencarian (24 Lokasi)</h2>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Urutkan:</span>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white text-gray-700 outline-none shadow-sm">
                    <option>Terdekat</option>
                    <option>Termurah</option>
                    <option>Rating Tertinggi</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($locations as $lokasi)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group">
                <div class="relative h-48 overflow-hidden">
                    @if($lokasi->image)
                        <img src="{{ Storage::url($lokasi->image) }}" alt="{{ $lokasi->nama_lokasi }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="{{ $lokasi->nama_lokasi }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @endif
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-blue-600 flex items-center gap-1">
                        <i class='bx bxs-star text-yellow-400'></i> 4.8 (120 Ulasan)
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-900 text-lg">{{ $lokasi->nama_lokasi }}</h3>
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">Tersedia</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4 flex items-start gap-1">
                        <i class='bx bx-map mt-1'></i> {{ $lokasi->alamat }}
                    </p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-2 py-1 bg-gray-50 text-gray-600 text-[10px] rounded border border-gray-100">CCTV 24 Jam</span>
                        <span class="px-2 py-1 bg-gray-50 text-gray-600 text-[10px] rounded border border-gray-100">Loker Kunci</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Tarif mulai</p>
                            <p class="font-bold text-blue-600">Rp {{ number_format($lokasi->tarif_per_hari, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/ hari</span></p>
                        </div>
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm font-medium rounded-lg transition">
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
</section>
@endsection
