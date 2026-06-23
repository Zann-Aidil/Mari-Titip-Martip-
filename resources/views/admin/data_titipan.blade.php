@extends('layouts.dashboard')

@section('title', 'Data Titipan - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">Titipan Barang</span>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Titipan Barang</h1>
            <p class="text-gray-500 text-sm">Kelola seluruh data transaksi penitipan barang</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.export.csv') }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center gap-2">
                <i class='bx bx-export'></i> Export CSV
            </a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg shadow-blue-200 flex items-center gap-2">
                <i class='bx bx-plus'></i> Buat Titipan
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-6">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            <div class="relative w-full max-w-xs">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" placeholder="Cari ID Titipan, pelanggan..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            </div>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none min-w-[150px]">
                <option>Semua Lokasi</option>
                <option>Kedai Joeli</option>
                <option>LaundryKuy</option>
            </select>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none min-w-[150px]">
                <option>Semua Status</option>
                <option>Tiba di Lokasi</option>
                <option>Dalam Proses</option>
                <option>Selesai</option>
            </select>
            <input type="date" class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none">
            <button class="px-5 py-2 border border-blue-200 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 transition flex items-center gap-2">
                <i class='bx bx-filter-alt'></i> Filter
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">ID Titipan</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Barang & Foto</th>
                        <th class="px-6 py-4 font-semibold">Lokasi</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($deposits as $deposit)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-medium text-blue-600">{{ $deposit->tracking_code }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $deposit->user->name ?? 'Unknown' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($deposit->foto_barang)
                                    <img src="{{ Storage::url($deposit->foto_barang) }}" alt="Foto Barang" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class='bx bx-image-alt text-xl'></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $deposit->nama_barang }}</p>
                                    <p class="text-xs text-gray-500">{{ $deposit->jumlah_barang }} Item</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $deposit->location->nama_lokasi ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $deposit->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if($deposit->status == 'pending')
                                <span class="px-2 py-1 bg-yellow-50 text-yellow-600 text-xs font-semibold rounded">Menunggu</span>
                            @elseif($deposit->status == 'accepted')
                                <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Diterima</span>
                            @else
                                <span class="px-2 py-1 bg-gray-50 text-gray-600 text-xs font-semibold rounded">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.deposits.update_status', $deposit->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-2 py-1 text-xs outline-none focus:border-blue-500">
                                    <option value="pending" {{ $deposit->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="accepted" {{ $deposit->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="retrieved" {{ $deposit->status == 'retrieved' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data titipan barang.
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
