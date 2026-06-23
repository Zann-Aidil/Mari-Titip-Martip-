@extends('layouts.dashboard')

@section('title', 'Riwayat Titipan - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Riwayat Titipan</h1>
        <p class="text-gray-500 text-sm">Lihat semua riwayat transaksi penitipan barang Anda.</p>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-8">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none">
                <option>Semua Status</option>
                <option>Dalam Proses</option>
                <option>Selesai</option>
                <option>Dibatalkan</option>
            </select>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none">
                <option>Bulan Ini</option>
                <option>Bulan Lalu</option>
                <option>Tahun Ini</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                        <th class="px-6 py-4 font-semibold">Barang</th>
                        <th class="px-6 py-4 font-semibold">Lokasi</th>
                        <th class="px-6 py-4 font-semibold">Waktu</th>
                        <th class="px-6 py-4 font-semibold">Biaya</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($deposits as $deposit)
                    <tr onclick="window.location='{{ route('user.tracking', $deposit->id) }}'" class="hover:bg-gray-50/50 transition cursor-pointer">
                        <td class="px-6 py-4 font-medium text-blue-600">{{ $deposit->tracking_code }}</td>
                        <td class="px-6 py-4">{{ $deposit->nama_barang }} ({{ $deposit->jumlah_barang }} Item)</td>
                        <td class="px-6 py-4 text-gray-600">{{ $deposit->location->nama_lokasi ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $deposit->created_at->format('d M Y') }} - {{ $deposit->durasi }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($deposit->total_biaya, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($deposit->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-50 text-yellow-600 text-xs font-semibold rounded">Menunggu</span>
                            @elseif($deposit->status == 'accepted')
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded">Diterima</span>
                            @else
                                <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada riwayat titipan.
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
