@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Welcome Header -->
    <div class="mb-8 flex justify-between items-center" data-aos="fade-down">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Halo, Super Admin!</h1>
            <p class="text-gray-500 text-sm">Berikut ringkasan aktivitas sistem hari ini</p>
        </div>
        <a href="{{ route('admin.locations.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class='bx bx-plus'></i> Tambah Lokasi
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Titipan -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden" data-aos="zoom-in" data-aos-delay="50">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class='bx bx-package text-2xl'></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Titipan</p>
                    <div class="flex items-baseline justify-end gap-2">
                        <h3 class="text-3xl font-bold text-gray-900">{{ $totalTitipan }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiba di Lokasi (Diterima) -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden" data-aos="zoom-in" data-aos-delay="100">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                    <i class='bx bx-check-circle text-2xl'></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tiba di Lokasi</p>
                    <div class="flex items-baseline justify-end gap-2">
                        <h3 class="text-3xl font-bold text-gray-900">{{ $titipanTiba }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dalam Pengambilan (Menunggu) -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden" data-aos="zoom-in" data-aos-delay="150">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                    <i class='bx bx-time-five text-2xl'></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Menunggu</p>
                    <div class="flex items-baseline justify-end gap-2">
                        <h3 class="text-3xl font-bold text-gray-900">{{ $dalamPengambilan }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden" data-aos="zoom-in" data-aos-delay="200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <i class='bx bx-check-double text-2xl'></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Selesai</p>
                    <div class="flex items-baseline justify-end gap-2">
                        <h3 class="text-3xl font-bold text-gray-900">{{ $selesai }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Lists Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Grafik Titipan -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6" data-aos="fade-up" data-aos-delay="250">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">Grafik Titipan</h2>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white text-gray-700 outline-none">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <!-- Placeholder for chart -->
            <div class="h-64 flex flex-col items-center justify-center text-gray-400 border border-dashed border-gray-200 rounded-xl relative overflow-hidden">
                <div class="flex gap-4 mb-4 text-xs">
                    <span class="flex items-center gap-1 text-blue-600"><div class="w-2 h-2 rounded-full bg-blue-600"></div> Total Titipan</span>
                    <span class="flex items-center gap-1 text-green-500"><div class="w-2 h-2 rounded-full bg-green-500"></div> Tiba di Lokasi</span>
                </div>
                <!-- Simulated Line Chart -->
                <svg class="w-full h-40 px-4" viewBox="0 0 400 100" preserveAspectRatio="none">
                    <polyline fill="none" stroke="#2563EB" stroke-width="2" points="0,80 50,40 100,60 150,20 200,60 250,30 300,70 350,20 400,60" />
                    <polyline fill="none" stroke="#10B981" stroke-width="2" points="0,90 50,60 100,80 150,40 200,70 250,50 300,80 350,40 400,70" />
                </svg>
            </div>
        </div>

        <!-- Lokasi Teraktif -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">Lokasi Teraktif</h2>
                <a href="{{ route('admin.locations.index') }}" class="text-sm font-semibold text-blue-600">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($lokasiTeraktif as $index => $lok)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">{{ $index + 1 }}</div>
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-900"><i class='bx bx-store-alt text-blue-500'></i> {{ $lok->nama_lokasi }}</div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $lok->deposits_count }} titipan</span>
                </div>
                @empty
                <p class="text-xs text-gray-500 text-center py-4">Belum ada lokasi aktif.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Titipan Terbaru</h2>
            <a href="{{ route('admin.deposits.index') }}" class="text-sm font-semibold text-blue-600">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Barang</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($titipanTerbaru as $titipan)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($titipan->foto_barang)
                                    <img src="{{ Storage::url($titipan->foto_barang) }}" alt="Item" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class='bx bx-image-alt text-xl'></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $titipan->nama_barang }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $titipan->tracking_code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($titipan->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-xs font-semibold rounded-full">Menunggu</span>
                            @elseif($titipan->status == 'accepted')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">Diterima</span>
                            @elseif($titipan->status == 'completed')
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $titipan->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            Belum ada titipan baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
