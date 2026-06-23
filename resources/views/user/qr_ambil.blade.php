@extends('layouts.dashboard')

@section('title', 'QR Code Ambil Barang - MARTIP')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="#" class="hover:text-blue-600">Dashboard</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">QR Ambil Barang</span>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">QR Code Ambil Barang</h1>
        <p class="text-gray-500 text-sm">Tunjukkan QR Code ini kepada petugas saat pengambilan barang</p>
    </div>

    <!-- Info Alert -->
    <div class="mb-8 bg-blue-50 text-blue-700 p-4 rounded-xl text-sm flex items-center gap-3 border border-blue-100">
        <i class='bx bx-info-circle text-xl'></i>
        QR Code ini hanya berlaku untuk 1x penggunaan dan akan kedaluwarsa pada waktu yang ditentukan.
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left Column: Details -->
        <div class="flex-1 space-y-6">
            
            <!-- Informasi Titipan -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Titipan</h2>
                    <span class="px-4 py-1.5 bg-green-50 text-green-600 text-xs font-bold rounded-lg border border-green-100">Siap Diambil</span>
                </div>
                
                <!-- Location Info -->
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ $deposit->location->image ?? 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' }}" alt="Lokasi" class="w-24 h-16 rounded-xl object-cover border border-gray-100">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $deposit->location->nama_lokasi ?? 'Lokasi Titip' }}</h3>
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1"><i class='bx bx-map'></i> {{ $deposit->location->alamat ?? '-' }}</p>
                        <p class="text-xs font-bold text-green-600 mt-1">Siap Diambil</p>
                    </div>
                </div>

                <hr class="border-gray-100 mb-6">

                <!-- Grid Details -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4 mb-6">
                    <div>
                        <p class="text-[11px] text-gray-500 mb-1">ID Titipan</p>
                        <p class="font-bold text-gray-900 text-sm">{{ $transactionId }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-500 mb-1">Jenis Barang</p>
                        <p class="font-bold text-gray-900 text-sm">{{ $deposit->nama_barang ?? 'Tas Ransel' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-500 mb-1">Jumlah</p>
                        <p class="font-bold text-gray-900 text-sm">{{ $deposit->jumlah_barang ?? 1 }} Item</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-500 mb-1">Durasi</p>
                        <p class="font-bold text-gray-900 text-sm">{{ $deposit ? current(explode(' ', $deposit->durasi)) : 1 }} Jam</p>
                    </div>
                </div>

                <hr class="border-gray-100 mb-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <p class="text-[11px] text-gray-500 mb-1">Tanggal Titip</p>
                        <div class="flex items-center gap-4">
                            <p class="font-bold text-gray-900 text-sm flex items-center gap-1"><i class='bx bx-calendar text-gray-400'></i> {{ $deposit->created_at->format('d M Y') }}</p>
                            <p class="font-bold text-gray-900 text-sm flex items-center gap-1"><i class='bx bx-time text-gray-400'></i> {{ $deposit->created_at->format('H:i') }} WIB</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-500 mb-1">Estimasi Tanggal Ambil</p>
                        <div class="flex items-center gap-4">
                            @php
                                $hours = (int) current(explode(' ', $deposit->durasi));
                                $estimasi = $deposit->created_at->addHours($hours);
                            @endphp
                            <p class="font-bold text-gray-900 text-sm flex items-center gap-1"><i class='bx bx-calendar text-gray-400'></i> {{ $estimasi->format('d M Y') }}</p>
                            <p class="font-bold text-gray-900 text-sm flex items-center gap-1"><i class='bx bx-time text-gray-400'></i> {{ $estimasi->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-green-50 p-5 rounded-xl border border-green-100">
                    <h4 class="text-sm font-bold text-green-800 mb-3">Syarat Pengambilan</h4>
                    <ul class="space-y-2 text-xs text-green-700">
                        <li class="flex items-start gap-2"><i class='bx bx-check mt-0.5'></i> Tunjukkan QR Code ini kepada petugas</li>
                        <li class="flex items-start gap-2"><i class='bx bx-check mt-0.5'></i> Pastikan data identitas sesuai dengan data pemilik titipan</li>
                        <li class="flex items-start gap-2"><i class='bx bx-check mt-0.5'></i> Barang hanya dapat diambil oleh pemilik titipan</li>
                    </ul>
                </div>
            </div>

            <!-- Petunjuk Pengambilan -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-6">Petunjuk Pengambilan</h2>
                <div class="flex items-start justify-between relative">
                    <!-- Progress Line -->
                    <div class="absolute top-6 left-[10%] right-[10%] h-0.5 bg-gray-100 -z-10"></div>
                    
                    <div class="text-center w-1/4">
                        <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-3 border border-blue-100"><i class='bx bx-map-pin'></i></div>
                        <p class="text-xs font-bold text-gray-900">Datang ke Lokasi</p>
                        <p class="text-[10px] text-gray-500 mt-1">Kunjungi lokasi titipan sesuai informasi di atas</p>
                    </div>
                    
                    <div class="text-center w-1/4">
                        <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-3 border border-blue-100"><i class='bx bx-qr'></i></div>
                        <p class="text-xs font-bold text-gray-900">Tunjukkan QR Code</p>
                        <p class="text-[10px] text-gray-500 mt-1">Tunjukkan QR Code ini kepada petugas</p>
                    </div>
                    
                    <div class="text-center w-1/4">
                        <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-3 border border-blue-100"><i class='bx bx-id-card'></i></div>
                        <p class="text-xs font-bold text-gray-900">Verifikasi Data</p>
                        <p class="text-[10px] text-gray-500 mt-1">Petugas akan memverifikasi identitas dan data titipan</p>
                    </div>
                    
                    <div class="text-center w-1/4">
                        <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-3 border border-blue-100"><i class='bx bx-package'></i></div>
                        <p class="text-xs font-bold text-gray-900">Ambil Barang</p>
                        <p class="text-[10px] text-gray-500 mt-1">Barang akan diserahkan jika verifikasi berhasil</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: QR Code -->
        <div class="w-full lg:w-[400px] space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center sticky top-8">
                <h2 class="text-base font-bold text-gray-900 mb-6">QR Code Pengambilan</h2>
                
                <!-- QR Code Box -->
                <div class="inline-block p-4 border-2 border-blue-100 rounded-2xl mb-6 shadow-sm">
                    <!-- Menggunakan Image dari Response API Onopay / Fallback -->
                    <img src="{{ $qrUrl }}" alt="QR Code" class="w-48 h-48 mx-auto" onerror="this.onerror=null;this.src='https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ $transactionId }}';">
                </div>
                
                <p class="text-[11px] text-gray-500 mb-1">ID Titipan</p>
                <p class="font-bold text-blue-600 text-lg tracking-wide mb-6">{{ $transactionId }}</p>
                
                <p class="text-[11px] text-gray-500 mb-1">Berlaku hingga</p>
                <p class="font-bold text-gray-900 text-sm flex justify-center items-center gap-1 mb-8"><i class='bx bx-time text-blue-600'></i> {{ $estimasi->format('d M Y, H:i') }} WIB</p>
                
                <div class="bg-blue-50 p-4 rounded-xl text-left border border-blue-100">
                    <p class="text-sm font-bold text-blue-800 flex items-center gap-2 mb-1"><i class='bx bx-check-shield'></i> Keamanan Terjamin</p>
                    <p class="text-xs text-blue-700">QR Code ini bersifat unik dan terenkripsi. Jangan berikan kepada siapapun.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                <h3 class="font-bold text-gray-900 mb-2">Butuh Bantuan?</h3>
                <p class="text-xs text-gray-500 mb-4">Jika ada kendala saat pengambilan barang, silakan hubungi customer service kami.</p>
                <button class="w-full py-3 border border-green-500 text-green-600 rounded-xl font-medium hover:bg-green-50 transition flex items-center justify-center gap-2">
                    <i class='bx bx-headphone'></i> Hubungi CS
                </button>
            </div>
        </div>
        
    </div>
</div>
@endsection
