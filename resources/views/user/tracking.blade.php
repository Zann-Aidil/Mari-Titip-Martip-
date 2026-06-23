@extends('layouts.dashboard')

@section('title', 'Tracking Barang - MARTIP')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">Tracking Barang</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Tracking Status Titipan</h1>
        <p class="text-gray-500 text-sm">Pantau status terkini dari barang yang Anda titipkan.</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        
        <!-- Header Info -->
        <div class="bg-blue-50/50 p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">ID Titipan</p>
                <h2 class="text-xl font-black text-blue-600">{{ $deposit->tracking_code }}</h2>
            </div>
            <div class="flex gap-4">
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Status Saat Ini</p>
                    @if($deposit->status == 'pending')
                        <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-lg border border-yellow-200">Menunggu (Belum Diantar)</span>
                    @elseif($deposit->status == 'accepted')
                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg border border-blue-200">Dalam Proses Penitipan</span>
                    @else
                        <span class="px-3 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200">Selesai Diambil</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Timeline -->
            <div class="relative pl-6 border-l-2 border-gray-100 space-y-10">
                
                <!-- Step 1 -->
                <div class="relative">
                    <div class="absolute -left-[35px] top-0 w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white border-4 border-white shadow-sm">
                        <i class='bx bx-check'></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Pembayaran Berhasil</h3>
                        <p class="text-sm text-gray-500 mt-1">Pembayaran telah dikonfirmasi. Silakan antar barang Anda ke lokasi.</p>
                        <p class="text-xs font-bold text-gray-400 mt-2">{{ $deposit->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <!-- Step 2 (Tiba di Lokasi) -->
                <div class="relative {{ $deposit->status == 'pending' ? 'opacity-50' : '' }}">
                    <div class="absolute -left-[35px] top-0 w-8 h-8 rounded-full {{ $deposit->status != 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400 border-4 border-white' }} flex items-center justify-center shadow-sm">
                        @if($deposit->status != 'pending')
                        <i class='bx bx-check'></i>
                        @else
                        <i class='bx bx-store-alt'></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold {{ $deposit->status != 'pending' ? 'text-gray-900' : 'text-gray-500' }}">Tiba di Lokasi</h3>
                        <p class="text-sm text-gray-500 mt-1">Barang telah diserahkan kepada petugas {{ $deposit->location->nama_lokasi ?? 'Lokasi' }} dan disimpan di loker.</p>
                        @if($deposit->status != 'pending')
                        <p class="text-xs font-bold text-gray-400 mt-2">{{ $deposit->updated_at->format('d M Y, H:i') }} WIB</p>
                        @endif
                    </div>
                </div>

                <!-- Step 3 (Dalam Penitipan) -->
                <div class="relative {{ $deposit->status == 'pending' ? 'opacity-50' : '' }}">
                    @if($deposit->status == 'accepted')
                    <div class="absolute -left-[35px] top-0 w-8 h-8 rounded-full bg-white flex items-center justify-center border-4 border-blue-600 shadow-sm">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                    </div>
                    @else
                    <div class="absolute -left-[35px] top-0 w-8 h-8 rounded-full {{ $deposit->status == 'completed' ? 'bg-blue-600 text-white border-4 border-white' : 'bg-gray-100 text-gray-400 border-4 border-white' }} flex items-center justify-center shadow-sm">
                        @if($deposit->status == 'completed')
                        <i class='bx bx-check'></i>
                        @else
                        <i class='bx bx-box'></i>
                        @endif
                    </div>
                    @endif
                    <div>
                        <h3 class="font-bold {{ $deposit->status == 'accepted' ? 'text-blue-600' : ($deposit->status == 'completed' ? 'text-gray-900' : 'text-gray-500') }}">Dalam Proses Penitipan</h3>
                        <p class="text-sm text-gray-500 mt-1">Barang Anda sedang disimpan dengan aman.</p>
                        @if($deposit->status == 'accepted')
                        <p class="text-xs font-bold text-gray-400 mt-2">Sedang berlangsung</p>
                        @endif
                    </div>
                </div>

                <!-- Step 4 (Selesai) -->
                <div class="relative {{ $deposit->status != 'completed' ? 'opacity-50' : '' }}">
                    <div class="absolute -left-[35px] top-0 w-8 h-8 rounded-full {{ $deposit->status == 'completed' ? 'bg-blue-600 text-white border-4 border-white shadow-sm' : 'bg-gray-100 text-gray-400 border-4 border-white' }} flex items-center justify-center">
                        <i class='bx bx-check-double'></i>
                    </div>
                    <div>
                        <h3 class="font-bold {{ $deposit->status == 'completed' ? 'text-gray-900' : 'text-gray-500' }}">Selesai Diambil</h3>
                        <p class="text-sm text-gray-500 mt-1">Barang telah berhasil diambil oleh Anda.</p>
                        @if($deposit->status == 'completed')
                        <p class="text-xs font-bold text-gray-400 mt-2">{{ $deposit->updated_at->format('d M Y, H:i') }} WIB</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Action Area -->
        @if($deposit->status != 'completed')
        <div class="p-6 bg-gray-50 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-600 mb-4">Ingin mengambil barang lebih awal?</p>
            <a href="{{ route('user.qr_ambil', ['deposit_id' => $deposit->id]) }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 text-gray-700 px-6 py-2.5 rounded-xl font-medium transition shadow-sm text-sm">
                <i class='bx bx-qr'></i> Tampilkan QR Pengambilan
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
