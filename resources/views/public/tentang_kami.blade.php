@extends('layouts.public')

@section('title', 'Tentang Kami - MARTIP')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tentang MARTIP</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Kami hadir untuk memberikan solusi penitipan barang yang aman, praktis, dan terpercaya di seluruh Indonesia.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Tentang Kami" class="rounded-3xl shadow-xl">
            </div>
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-gray-900">Misi Kami</h2>
                <p class="text-gray-600 leading-relaxed">
                    MARTIP didirikan dengan satu tujuan sederhana: menghilangkan kekhawatiran Anda saat bepergian atau berkegiatan tanpa harus membawa barang bawaan yang berat. Kami bermitra dengan ratusan lokasi strategis mulai dari kafe, laundry, hingga indekos yang telah diverifikasi ketat untuk memastikan barang Anda aman.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class='bx bx-check-shield text-2xl text-green-500 mt-1'></i>
                        <div>
                            <h4 class="font-bold text-gray-900">Keamanan Terjamin</h4>
                            <p class="text-sm text-gray-600">Standar keamanan ketat dengan CCTV dan pengawasan di setiap lokasi mitra.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class='bx bx-map-pin text-2xl text-blue-500 mt-1'></i>
                        <div>
                            <h4 class="font-bold text-gray-900">Lokasi Strategis</h4>
                            <p class="text-sm text-gray-600">Mudah ditemukan di dekat stasiun, terminal, pusat kota, dan tempat wisata.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
