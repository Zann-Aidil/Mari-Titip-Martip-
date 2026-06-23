@extends('layouts.dashboard')

@section('title', 'Form Titipan - MARTIP')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('user.lokasi.detail', $location->id) }}" class="hover:text-blue-600">Detail Lokasi</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">Form Titipan</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Form Titipan Barang</h1>
        <p class="text-gray-500 text-sm">Isi detail barang yang ingin Anda titipkan.</p>
    </div>

    <!-- Stepper -->
    <div class="flex items-center mb-10 max-w-2xl">
        <div class="flex items-center gap-2 text-blue-600">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">1</div>
            <span class="font-bold text-sm">Detail Barang</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-4"></div>
        <div class="flex items-center gap-2 text-gray-400">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-sm">2</div>
            <span class="font-bold text-sm">Pembayaran</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-4"></div>
        <div class="flex items-center gap-2 text-gray-400">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-sm">3</div>
            <span class="font-bold text-sm">Selesai</span>
        </div>
    </div>

    <form action="{{ route('user.titip.store', $location->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Form -->
            <div class="flex-1 space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Detail Barang</h2>
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Jenis & Jumlah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Barang</label>
                            <input type="text" name="nama_barang" placeholder="Contoh: Tas Ransel" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah</label>
                            <div class="relative">
                                <input type="number" name="jumlah_barang" placeholder="1" class="w-full pl-4 pr-12 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-sm" required>
                                <input type="hidden" name="durasi" id="durasi_input" value="1">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Item</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Mulai Titip</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-sm text-gray-600" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Rencana Pengambilan</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-sm text-gray-600" required>
                        </div>
                    </div>

                    <!-- Foto Barang -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Barang</label>
                        <label for="item_image" class="block border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:bg-gray-50 transition cursor-pointer relative overflow-hidden" id="uploadContainer">
                            <div id="uploadPrompt">
                                <i class='bx bx-cloud-upload text-4xl text-gray-400 mb-2'></i>
                                <p class="text-sm text-gray-600 font-medium">Klik untuk upload foto barang</p>
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                            <img id="imagePreview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                            <input type="file" name="item_image" id="item_image" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>

                    <!-- Script Preview Image -->
                    <script>
                        function previewImage(event) {
                            const input = event.target;
                            if (input.files && input.files[0]) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    document.getElementById('imagePreview').src = e.target.result;
                                    document.getElementById('imagePreview').classList.remove('hidden');
                                    document.getElementById('uploadPrompt').classList.add('hidden');
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                    </script>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Contoh: Tolong simpan di tempat yang kering." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-sm"></textarea>
                    </div>

                </div>
            </div>

            <!-- Right: Ringkasan Lokasi -->
            <div class="w-full lg:w-[350px]">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sticky top-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Lokasi</h2>
                    
                    <div class="flex items-center gap-4 mb-6">
                        @if($location->image)
                            <img src="{{ Storage::url($location->image) }}" alt="Lokasi" class="w-16 h-16 rounded-xl object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Lokasi" class="w-16 h-16 rounded-xl object-cover">
                        @endif
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">{{ $location->nama_lokasi }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5"><i class='bx bx-map'></i> {{ $location->alamat }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs text-gray-500">Tarif Dasar</span>
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($location->tarif_per_hari, 0, ',', '.') }} / hari</span>
                        </div>
                        <div class="flex justify-between items-center mb-2 border-t border-gray-200 pt-2">
                            <span class="text-xs text-gray-500">Durasi Titip</span>
                            <span class="text-sm font-bold text-gray-900" id="durasi_text">1 Hari</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-2 mt-2">
                            <span class="text-sm text-gray-700 font-bold">Total Estimasi</span>
                            <span class="text-lg font-black text-blue-600" id="total_harga">Rp {{ number_format($location->tarif_per_hari, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">*Total biaya akan dihitung pasti berdasarkan durasi penitipan.</p>
                    </div>

                    <!-- Script Kalkulasi Harga -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const startTime = document.getElementById('start_time');
                            const endTime = document.getElementById('end_time');
                            const durasiInput = document.getElementById('durasi_input');
                            const durasiText = document.getElementById('durasi_text');
                            const totalHarga = document.getElementById('total_harga');
                            const tarifPerHari = {{ $location->tarif_per_hari }};

                            function updatePrice() {
                                if (startTime.value && endTime.value) {
                                    let start = new Date(startTime.value);
                                    let end = new Date(endTime.value);
                                    if (end > start) {
                                        let diffMs = end - start;
                                        let days = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
                                        if (days < 1) days = 1;
                                        
                                        durasiInput.value = days;
                                        durasiText.innerText = days + " Hari";
                                        
                                        let total = days * tarifPerHari;
                                        totalHarga.innerText = "Rp " + total.toLocaleString('id-ID');
                                        return;
                                    }
                                }
                                // Default fallback
                                durasiInput.value = 1;
                                durasiText.innerText = "1 Hari";
                                totalHarga.innerText = "Rp " + tarifPerHari.toLocaleString('id-ID');
                            }

                            startTime.addEventListener('change', updatePrice);
                            endTime.addEventListener('change', updatePrice);
                        });
                    </script>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        Lanjut ke Pembayaran <i class='bx bx-right-arrow-alt text-lg'></i>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
