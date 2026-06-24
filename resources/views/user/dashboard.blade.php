@extends('layouts.dashboard')

@section('title', 'Dashboard User - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard</h1>
        <p class="text-gray-500 text-sm">Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}! 👋</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Titipan Aktif -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <i class='bx bx-package text-2xl'></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $activeCount }}</h3>
                <p class="text-sm font-semibold text-gray-700">Titipan Aktif</p>
                <p class="text-[10px] text-gray-500 mt-1">Sedang dalam proses</p>
            </div>
        </div>

        <!-- Titipan Selesai -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <i class='bx bx-check-circle text-2xl'></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $completedCount }}</h3>
                <p class="text-sm font-semibold text-gray-700">Titipan Selesai</p>
                <p class="text-[10px] text-gray-500 mt-1">Total selesai</p>
            </div>
        </div>

        <!-- Menunggu Diambil / Diproses -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600 flex-shrink-0">
                <i class='bx bx-time-five text-2xl'></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</h3>
                <p class="text-sm font-semibold text-gray-700">Menunggu</p>
                <p class="text-[10px] text-gray-500 mt-1">Pending/Diambil</p>
            </div>
        </div>

        <!-- Lokasi Tersedia -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                <i class='bx bx-map-pin text-2xl'></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ count($locations) }}</h3>
                <p class="text-sm font-semibold text-gray-700">Lokasi Tersedia</p>
                <p class="text-[10px] text-gray-500 mt-1">Di kotamu</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Titipan Aktif List -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col" data-aos="fade-right">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white">
                <h2 class="text-lg font-bold text-gray-900">Titipan Terbaru</h2>
                <a href="{{ route('user.riwayat') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua</a>
            </div>
            <div class="p-6 space-y-4">
                
                @forelse($deposits as $titipan)
                <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition cursor-pointer group" onclick="window.location.href='{{ route('user.pembayaran', ['deposit_id' => $titipan->id]) }}'">
                    @if($titipan->foto_barang)
                        <img src="{{ Storage::url($titipan->foto_barang) }}" alt="Barang" class="w-16 h-16 rounded-xl object-cover">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xs">Tanpa Foto</div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">{{ $titipan->nama_barang }}</h4>
                        <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                            <i class='bx bx-map'></i> {{ $titipan->location->nama_lokasi ?? 'Lokasi' }}
                        </div>
                        <div class="flex items-center gap-1 text-[10px] text-gray-400 mt-1">
                            <i class='bx bx-calendar'></i> {{ $titipan->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @if($titipan->status == 'pending')
                            <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-xs font-semibold rounded-full">Menunggu</span>
                        @elseif($titipan->status == 'accepted')
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">Diterima</span>
                        @elseif($titipan->status == 'retrieved')
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full">Selesai</span>
                        @endif
                        <i class='bx bx-chevron-right text-gray-300 group-hover:text-blue-600 transition'></i>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-gray-500 text-sm">Belum ada titipan aktif.</p>
                </div>
                @endforelse

            </div>
        </div>
        <!-- Lokasi Terdekat List -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col" data-aos="fade-left">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white">
                <h2 class="text-lg font-bold text-gray-900">Lokasi Terdekat</h2>
                <a href="{{ route('user.lokasi') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua</a>
            </div>
            <div class="p-6 space-y-4">
                
                @forelse($locations as $lokasi)
                <div onclick="window.location.href='{{ route('user.lokasi.detail', $lokasi->id) }}'" class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition cursor-pointer">
                    @if($lokasi->image)
                        <img src="{{ asset('storage/' . $lokasi->image) }}" class="w-20 h-16 rounded-xl object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" class="w-20 h-16 rounded-xl object-cover">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 text-sm">{{ $lokasi->nama_lokasi }}</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5"><i class='bx bx-map'></i> {{ $lokasi->alamat }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-green-600 mt-1">Rp {{ number_format($lokasi->tarif_per_hari, 0, ',', '.') }} / hari</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500">Belum ada lokasi.</p>
                @endforelse

            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="mt-8 bg-blue-600 rounded-3xl p-8 flex items-center justify-between text-white shadow-lg shadow-blue-200" data-aos="fade-up">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white text-2xl">
                <i class='bx bx-shield-quarter'></i>
            </div>
            <div>
                <h3 class="text-lg font-bold">Titip dengan Tenang</h3>
                <p class="text-sm text-blue-100">Semua lokasi penitipan telah diverifikasi dan dilengkapi keamanan.</p>
            </div>
        </div>
        <button class="px-6 py-2.5 bg-white text-blue-600 font-medium rounded-xl border border-blue-200 hover:bg-blue-50 transition text-sm">
            Pelajari Lebih Lanjut
        </button>
    </div>

</div>
@endsection
