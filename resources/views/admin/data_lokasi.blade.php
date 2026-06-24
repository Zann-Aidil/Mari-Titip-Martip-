@extends('layouts.dashboard')

@section('title', 'Petugas Lokasi - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">Lokasi Titip</span>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Lokasi Titip</h1>
            <p class="text-gray-500 text-sm">Kelola data lokasi titip barang</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class='bx bx-plus'></i> Tambah Lokasi
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-6">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            <div class="relative w-full max-w-xs">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" placeholder="Cari lokasi, kota, atau alamat..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            </div>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none min-w-[150px]">
                <option>Semua Kota</option>
                <option>Medan</option>
                <option>Jakarta</option>
                <option>Bandung</option>
            </select>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none min-w-[150px]">
                <option>Semua Status</option>
                <option>Aktif</option>
                <option>Nonaktif</option>
            </select>
            <button class="px-5 py-2 border border-blue-200 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 transition flex items-center gap-2">
                <i class='bx bx-filter-alt'></i> Filter
            </button>
        </div>
        <button class="px-5 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center gap-2 border border-gray-200">
            <i class='bx bx-reset'></i> Reset
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Lokasi</th>
                        <th class="px-6 py-4 font-semibold">Alamat</th>
                        <th class="px-6 py-4 font-semibold">Kota</th>
                        <th class="px-6 py-4 font-semibold">Tarif/Hari</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($locations as $index => $lokasi)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-gray-500">{{ $locations->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($lokasi->image)
                                    <img src="{{ asset('storage/' . $lokasi->image) }}" class="w-8 h-8 rounded-lg object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500"><i class='bx bx-store-alt'></i></div>
                                @endif
                                <span class="font-bold text-gray-900">{{ $lokasi->nama_lokasi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <p>{{ $lokasi->alamat }}</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">Medan</td>
                        <td class="px-6 py-4 text-gray-900 font-bold">Rp {{ number_format($lokasi->tarif_per_hari, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($lokasi->status == 'active')
                                <span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $lokasi->id }}, '{{ addslashes($lokasi->nama_lokasi) }}', '{{ addslashes($lokasi->alamat) }}', {{ $lokasi->tarif_per_hari }})" class="w-8 h-8 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 flex items-center justify-center transition"><i class='bx bx-edit-alt'></i></button>
                                <form action="{{ route('admin.locations.destroy', $lokasi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 flex items-center justify-center transition"><i class='bx bx-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data lokasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100">
            {{ $locations->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Lokasi -->
<div id="lokasiModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Tambah Lokasi</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="lokasiForm" action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" id="inputNama" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" id="inputAlamat" rows="3" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tarif per Hari (Rp)</label>
                <input type="number" name="tarif_per_hari" id="inputTarif" required min="0" value="5000" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Foto Lokasi (Opsional)</label>
                <input type="file" name="image" id="inputImage" accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none bg-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, JPEG. Maks: 2MB.</p>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-50 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium hover:bg-blue-700 rounded-xl transition shadow-lg shadow-blue-200">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Lokasi';
        document.getElementById('lokasiForm').action = "{{ route('admin.locations.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('inputNama').value = '';
        document.getElementById('inputAlamat').value = '';
        document.getElementById('inputTarif').value = '5000';
        document.getElementById('lokasiModal').classList.remove('hidden');
    }

    function openEditModal(id, nama, alamat, tarif) {
        document.getElementById('modalTitle').innerText = 'Edit Lokasi';
        document.getElementById('lokasiForm').action = "/admin/locations/" + id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputNama').value = nama;
        document.getElementById('inputAlamat').value = alamat;
        document.getElementById('inputTarif').value = tarif;
        document.getElementById('lokasiModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('lokasiModal').classList.add('hidden');
    }
</script>

@endsection
