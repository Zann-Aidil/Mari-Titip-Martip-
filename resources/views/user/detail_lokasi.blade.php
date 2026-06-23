@extends('layouts.dashboard')

@section('title', 'Detail Lokasi - MARTIP')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('user.lokasi') }}" class="hover:text-blue-600">Lokasi Titip</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">{{ $lokasi->nama_lokasi }}</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left: Image & Info -->
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                @if($lokasi->image)
                    <img src="{{ Storage::url($lokasi->image) }}" alt="{{ $lokasi->nama_lokasi }}" class="w-full h-80 object-cover">
                @else
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="{{ $lokasi->nama_lokasi }}" class="w-full h-80 object-cover">
                @endif
                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg uppercase tracking-wider">Tersedia</span>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $lokasi->nama_lokasi }}</h1>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-6 flex items-start gap-2">
                        <i class='bx bx-map text-xl text-gray-400 mt-0.5'></i> 
                        <span>{{ $lokasi->alamat }}</span>
                    </p>

                    <h3 class="text-lg font-bold text-gray-900 mb-4">Fasilitas Keamanan</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center gap-2 bg-gray-50">
                            <i class='bx bx-cctv text-2xl text-blue-600'></i>
                            <span class="text-xs font-bold text-gray-700">CCTV 24 Jam</span>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center gap-2 bg-gray-50">
                            <i class='bx bx-lock-alt text-2xl text-blue-600'></i>
                            <span class="text-xs font-bold text-gray-700">Loker Kunci</span>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center gap-2 bg-gray-50">
                            <i class='bx bx-check-shield text-2xl text-blue-600'></i>
                            <span class="text-xs font-bold text-gray-700">Dijaga Petugas</span>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center gap-2 bg-gray-50">
                            <i class='bx bx-door-open text-2xl text-blue-600'></i>
                            <span class="text-xs font-bold text-gray-700">Ruang Khusus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Right: Booking Form Card -->
        <div class="w-full lg:w-[400px]">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 sticky top-8">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Informasi Tarif</h3>
                <p class="text-gray-500 text-sm mb-6">Tarif dihitung per barang per hari</p>

                <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 mb-8 flex justify-between items-center">
                    <span class="font-bold text-blue-800">Tarif Standar</span>
                    <span class="text-2xl font-black text-blue-600">Rp 5.000<span class="text-sm text-blue-500 font-normal">/hari</span></span>
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-green-500 text-xl mt-0.5'></i>
                        <p class="text-sm text-gray-600">Gratis biaya pembatalan sebelum 2 jam.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class='bx bx-check-circle text-green-500 text-xl mt-0.5'></i>
                        <p class="text-sm text-gray-600">Jaminan ganti rugi jika barang hilang.</p>
                    </div>
                </div>

                <a href="{{ route('user.titip.form', $lokasi->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-blue-200">
                    Lanjut Buat Titipan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
