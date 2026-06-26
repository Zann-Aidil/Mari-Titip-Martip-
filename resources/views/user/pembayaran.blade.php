@extends('layouts.dashboard')

@section('title', 'Konfirmasi & Pembayaran - MARTIP')

@section('content')
<div id="app" class="max-w-6xl mx-auto">

    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="#" class="hover:text-blue-600">Form Titipan</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">Konfirmasi & Pembayaran</span>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Konfirmasi & Pembayaran</h1>
        <p class="text-gray-500 text-sm">Periksa kembali detail titipan Anda sebelum melakukan pembayaran</p>
    </div>

    <div>
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Column: Details & Methods -->
            <div class="flex-1 space-y-8">
                
                <!-- Detail Titipan -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Detail Titipan</h2>
                        <a href="#" class="px-4 py-1.5 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition flex items-center gap-1">
                            <i class='bx bx-edit-alt'></i> Edit
                        </a>
                    </div>
                    
                    <!-- Location Info -->
                    <div class="flex items-center gap-4 mb-6">
                        @if($deposit->location?->image)
                            <img src="{{ Storage::url($deposit->location->image) }}" alt="Lokasi" class="w-24 h-16 rounded-xl object-cover border border-gray-100">
                        @else
                            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Lokasi" class="w-24 h-16 rounded-xl object-cover border border-gray-100">
                        @endif
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $deposit->location?->nama_lokasi ?? 'Lokasi Titip' }}</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1"><i class='bx bx-map'></i> {{ $deposit->location?->alamat ?? '-' }}</p>
                            <p class="text-xs font-bold text-green-600 mt-1">Sistem Aktif</p>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    <!-- Grid Details -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4">
                        <div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1"><i class='bx bx-package'></i> Jenis Barang</p>
                            <p class="font-semibold text-gray-900 text-sm">{{ $deposit->nama_barang }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1"><i class='bx bx-grid-alt'></i> Jumlah</p>
                            <p class="font-semibold text-gray-900 text-sm">{{ $deposit->jumlah_barang }} Item</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1"><i class='bx bx-time'></i> Durasi Titip</p>
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded font-medium">{{ current(explode(' ', $deposit->durasi)) }} Jam</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1"><i class='bx bx-message-square-detail'></i> Catatan</p>
                            <p class="font-semibold text-gray-900 text-sm">{{ $deposit->catatan ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1"><i class='bx bx-image-alt'></i> Foto Barang</p>
                            @if($deposit->foto_barang)
                                <img src="{{ Storage::url($deposit->foto_barang) }}" alt="Foto Barang" class="w-16 h-16 rounded-xl object-cover border border-gray-100 mt-1">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-gray-100 border border-gray-200 mt-1 flex items-center justify-center text-gray-400 text-xs text-center">
                                    Tanpa Foto
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Metode Pembayaran (QR Code OnoPay)</h2>
                    
                    <!-- Info Alert -->
                    <div class="mb-6 bg-blue-50 text-blue-700 p-4 rounded-xl text-xs flex items-center gap-2">
                        <i class='bx bx-info-circle text-lg'></i>
                        Silakan cek ringkasan pembayaran di sebelah kanan, dan klik "Bayar Sekarang" untuk memproses via QR Code OnoPay.
                    </div>
                </div>

            </div>

            <!-- Right Column: Ringkasan -->
            <div class="w-full lg:w-[400px]">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Pembayaran</h2>
                    
                    <div class="space-y-4 text-sm mb-6">
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-green-800 font-medium">ID Titipan</span>
                            <span class="text-green-800 font-bold">{{ $deposit->tracking_code }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Tarif Titip</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($deposit->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                            <span class="text-gray-500">Biaya Layanan</span>
                            <span class="font-semibold text-gray-900">Rp 0</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2">
                            <span class="font-bold text-gray-900">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-green-600">Rp {{ number_format($deposit->total_biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-yellow-50 text-yellow-800 p-4 rounded-xl text-xs flex items-start gap-2 mb-6">
                        <i class='bx bx-info-circle text-lg mt-0.5'></i>
                        <p>Dengan melanjutkan pembayaran, Anda menyetujui <a href="#" class="font-semibold text-blue-600">Syarat & Ketentuan</a> yang berlaku.</p>
                    </div>

                    <!-- Vue Onopay Button Component -->
                    <onopay-payment-form
                        :amount="{{ $deposit->total_biaya }}"
                        track-id="{{ $deposit->tracking_code }}"
                        :deposit-id="{{ $deposit->id }}"
                        user-phone="{{ Auth::user()->phone ?? '' }}"
                        merchant-phone="{{ config('onopay.merchant_phone') }}"
                    ></onopay-payment-form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
