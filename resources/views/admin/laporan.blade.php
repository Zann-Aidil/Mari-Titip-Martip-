@extends('layouts.dashboard')

@section('title', 'Laporan - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">Laporan Titipan</span>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Laporan Titipan</h1>
            <p class="text-gray-500 text-sm">Lihat statistik dan laporan lengkap transaksi titipan</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.export.csv') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg shadow-green-200 flex items-center gap-2">
                <i class='bx bx-spreadsheet'></i> Export CSV
            </a>
            <a href="{{ route('admin.export.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg shadow-red-200 flex items-center gap-2">
                <i class='bx bxs-file-pdf'></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                <i class='bx bx-wallet'></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-2xl">
                <i class='bx bx-package'></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Titipan</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalTitipan }} Transaksi</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl">
                <i class='bx bx-time'></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Titipan Berlangsung</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $titipanAktif }} Aktif</h3>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-900">Rincian Transaksi Titipan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Kode Tracking</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Lokasi</th>
                        <th class="px-6 py-4 font-semibold">Barang</th>
                        <th class="px-6 py-4 font-semibold">Total Biaya</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($deposits as $index => $deposit)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-gray-500">{{ $deposits->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $deposit->tracking_code }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $deposit->user->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $deposit->location->nama_lokasi }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $deposit->nama_barang }} ({{ $deposit->jumlah_barang }} item)</td>
                        <td class="px-6 py-4 text-gray-900 font-bold">Rp {{ number_format($deposit->total_biaya, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($deposit->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-50 text-yellow-600 text-xs font-semibold rounded">Pending</span>
                            @elseif($deposit->status == 'accepted')
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded">Diterima</span>
                            @elseif($deposit->status == 'retrieved')
                                <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Selesai</span>
                            @elseif($deposit->status == 'cancelled')
                                <span class="px-2 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded">Dibatalkan</span>
                            @else
                                <span class="px-2 py-1 bg-gray-50 text-gray-600 text-xs font-semibold rounded">{{ $deposit->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $deposit->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            Belum ada transaksi titipan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100">
            {{ $deposits->links() }}
        </div>
    </div>
</div>
@endsection
