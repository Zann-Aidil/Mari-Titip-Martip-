@extends('layouts.public')

@section('title', 'MARTIP - Mari Titip Barang')

@section('content')
<!-- Hero Section -->
<section class="pt-16 pb-20 bg-white overflow-hidden" data-aos="fade-down">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Left Text Content -->
            <div class="relative z-10" data-aos="fade-right">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Titip Barang<br>
                    Jadi Lebih <span class="text-green-600">Aman</span><br>
                    dan <span class="text-green-600">Terpercaya</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-lg leading-relaxed">
                    MARTIP (Mari Titip Barang) adalah platform penitipan barang yang aman, mudah, dan praktis di berbagai lokasi terpercaya. Pantau status titipan Anda secara real-time.
                </p>
                
                <!-- Search Box -->
                <div class="relative max-w-xl">
                    <div class="bg-white p-2 border border-gray-200 rounded-2xl shadow-sm flex items-center relative z-20">
                        <div class="pl-4 pr-3 text-blue-600">
                            <i class='bx bx-map text-2xl'></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari lokasi titip terdekat..." class="flex-1 py-3 focus:outline-none text-gray-700 bg-transparent" autocomplete="off">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2">
                            <i class='bx bx-search'></i> Cari Lokasi
                        </button>
                    </div>
                    
                    <!-- Search Results Dropdown -->
                    <div id="searchResults" class="absolute w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 hidden overflow-hidden transition-all max-h-60 overflow-y-auto">
                        <ul id="searchList" class="divide-y divide-gray-50">
                            <!-- Results will be injected here via AJAX -->
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Illustration -->
            <div class="relative flex justify-center lg:justify-end z-0 w-full" data-aos="fade-left">
                <!-- Decorational background blobs -->
                <div class="absolute top-10 right-10 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
                <div class="absolute bottom-10 left-10 w-80 h-80 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
                
                <div class="relative w-full max-w-lg lg:max-w-xl xl:max-w-2xl flex justify-center items-center">
                    <!-- Main Illustration -->
                    <img src="{{ asset('images/mockup 2.png') }}" alt="Martip Illustration" class="relative z-10 w-full h-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.15)] transition-transform hover:scale-105 duration-500 hover:drop-shadow-[0_30px_60px_rgba(0,0,0,0.2)]">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-10 bg-white" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
            
            <div class="flex items-center gap-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl flex-shrink-0">
                    <i class='bx bxs-map'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">120+</h3>
                    <p class="text-sm font-semibold text-gray-700">Lokasi Mitra</p>
                    <p class="text-xs text-gray-500 mt-1">Tersebar di berbagai kota</p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 pl-0 md:pl-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl flex-shrink-0">
                    <i class='bx bx-package'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">2.500+</h3>
                    <p class="text-sm font-semibold text-gray-700">Barang Dititipkan</p>
                    <p class="text-xs text-gray-500 mt-1">Barang aman setiap hari</p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 pl-0 lg:pl-6" data-aos="zoom-in" data-aos-delay="300">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0">
                    <i class='bx bx-check-shield'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">98%</h3>
                    <p class="text-sm font-semibold text-gray-700">Tingkat Kepuasan</p>
                    <p class="text-xs text-gray-500 mt-1">Pengguna merasa puas</p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 pl-0 md:pl-6" data-aos="zoom-in" data-aos-delay="400">
                <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0">
                    <i class='bx bx-time-five'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">24/7</h3>
                    <p class="text-sm font-semibold text-gray-700">Monitoring</p>
                    <p class="text-xs text-gray-500 mt-1">Pantau titipan kapan saja</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Partner Logos Section -->
<section class="py-12 bg-white" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h4 class="text-sm font-bold text-gray-900 mb-8 uppercase tracking-wider">Lokasi Titip Terpercaya</h4>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-70 grayscale hover:grayscale-0 transition duration-500">
            <!-- Dummy Logos replacing the specific ones in mockup -->
            <div class="flex items-center gap-2"><i class='bx bx-coffee text-3xl text-yellow-800'></i><span class="font-bold text-xl text-gray-800">Kedai Joeli</span></div>
            <div class="flex items-center gap-2"><i class='bx bx-water text-3xl text-blue-500'></i><span class="font-bold text-xl text-gray-800">LaundryKuy</span></div>
            <div class="flex items-center gap-2"><i class='bx bx-home-heart text-3xl text-pink-500'></i><span class="font-bold text-xl text-gray-800">Indekos Putri</span></div>
            <div class="flex items-center gap-2"><i class='bx bx-store text-3xl text-green-700'></i><span class="font-bold text-xl text-gray-800">Warung Pak Gede</span></div>
            <div class="flex items-center gap-2"><i class='bx bx-train text-3xl text-gray-600'></i><span class="font-bold text-xl text-gray-800">Stasiun Mini</span></div>
        </div>
    </div>
</section>

<!-- Download App Section (Unique Design) -->
<section class="py-24 bg-slate-900 relative overflow-hidden" data-aos="fade-up">
    <!-- Abstract Background Patterns -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-600/20 to-transparent"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-pulse"></div>
    <div class="absolute top-1/4 right-20 w-64 h-64 bg-cyan-400 rounded-full mix-blend-screen filter blur-[80px] opacity-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Content -->
            <div class="lg:col-span-7" data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-blue-400/30 bg-blue-500/10 text-cyan-300 text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-sm">
                    <i class='bx bx-mobile-alt text-lg'></i> Aplikasi Mobile Tersedia
                </div>
                
                <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6">
                    Satu Genggaman,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Beribu Kemudahan</span>
                </h2>
                
                <p class="text-lg text-slate-300 mb-10 leading-relaxed max-w-xl">
                    Nikmati pengalaman booking penitipan barang yang lebih cepat tanpa antre. Pantau status barangmu secara real-time langsung dari layar smartphone.
                </p>

                <!-- Download Buttons -->
                <div class="flex flex-wrap items-center gap-4">
                    
                    <!-- [UBAH LINK GOOGLE PLAY/DRIVE DI SINI] -->
                    <!-- Ganti tanda pagar '#' pada href di bawah ini dengan link Google Drive / Play Store milikmu -->
                    <a href="#" class="inline-flex items-center gap-3 bg-white hover:bg-gray-50 text-slate-900 font-bold py-3.5 px-8 rounded-2xl shadow-xl shadow-white/10 transition-all duration-300 transform hover:-translate-y-1">
                        <i class='bx bxl-play-store text-2xl text-green-600'></i>
                        <div class="text-left">
                            <span class="block text-[10px] text-gray-500 font-medium leading-none mb-1">DOWNLOAD DI</span>
                            <span class="block text-sm leading-none">Google Play</span>
                        </div>
                    </a>
                    
                    <!-- [UBAH LINK APP STORE DI SINI] -->
                    <!-- Ganti tanda pagar '#' pada href di bawah ini dengan link App Store milikmu (atau hapus tombol ini jika tidak perlu) -->
                    <a href="#" class="inline-flex items-center gap-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold py-3.5 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <i class='bx bxl-apple text-2xl'></i>
                        <div class="text-left">
                            <span class="block text-[10px] text-slate-400 font-medium leading-none mb-1">DOWNLOAD DI</span>
                            <span class="block text-sm leading-none">App Store</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Right Content - QR Code Scanner Aesthetic -->
            <div class="lg:col-span-5 flex justify-center lg:justify-end mt-10 lg:mt-0" data-aos="fade-left" data-aos-delay="200">
                <div class="relative w-full max-w-sm">
                    <!-- Glowing Border Effect Container -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 rounded-[2.5rem] blur opacity-30"></div>
                    
                    <div class="bg-slate-800 border border-slate-700 p-8 rounded-[2rem] shadow-2xl relative z-10 flex flex-col items-center">
                        <div class="w-full flex justify-between items-center mb-6">
                            <h4 class="text-white font-bold tracking-wide">Pindai QR</h4>
                            <i class='bx bx-scan text-cyan-400 text-2xl'></i>
                        </div>
                        
                        <!-- QR Code Frame with Scanner Animation -->
                        <div class="relative w-full aspect-square bg-white rounded-2xl p-3 mb-6 group overflow-hidden border border-gray-200">
                            <!-- Scanning Line Element -->
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-cyan-400 shadow-[0_0_20px_#22d3ee] z-20" style="animation: scan 2.5s ease-in-out infinite;"></div>
                            
                            <!-- Custom grid lines in background of QR -->
                            <div class="absolute inset-0 opacity-5" style="background-image: linear-gradient(#000 1px, transparent 1px), linear-gradient(90deg, #000 1px, transparent 1px); background-size: 10px 10px;"></div>
                            
                            <!-- [UBAH ISI QR CODE DI SINI] -->
                            <!-- Untuk mengubah isi QR Code, ganti teks setelah tulisan "data=" pada tag src di bawah ini. -->
                            <!-- Contoh: data=https://drive.google.com/folder-apk-kamu -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=https://drive.google.com/" alt="QR Code" class="w-full h-full object-contain relative z-10">
                            
                            <!-- Corner markers for scanner look -->
                            <div class="absolute top-3 left-3 w-8 h-8 border-t-4 border-l-4 border-cyan-500 rounded-tl-xl z-20"></div>
                            <div class="absolute top-3 right-3 w-8 h-8 border-t-4 border-r-4 border-cyan-500 rounded-tr-xl z-20"></div>
                            <div class="absolute bottom-3 left-3 w-8 h-8 border-b-4 border-l-4 border-cyan-500 rounded-bl-xl z-20"></div>
                            <div class="absolute bottom-3 right-3 w-8 h-8 border-b-4 border-r-4 border-cyan-500 rounded-br-xl z-20"></div>
                        </div>

                        <p class="text-sm text-slate-400 text-center font-medium px-4">Arahkan kamera smartphone Anda ke QR code untuk mengunduh</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Include CSS keyframes for scan animation -->
<style>
    @keyframes scan {
        0%, 100% { top: 5%; opacity: 0; }
        10%, 90% { opacity: 1; }
        50% { top: 95%; }
    }
</style>

<!-- Live Search Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchList = document.getElementById('searchList');

    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 1) {
            searchResults.classList.add('hidden');
            searchList.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/api/search-lokasi?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchList.innerHTML = '';
                    
                    if (data.length === 0) {
                        searchList.innerHTML = '<li class="p-4 text-center text-sm text-gray-500">Lokasi tidak ditemukan</li>';
                    } else {
                        data.forEach(lokasi => {
                            const li = document.createElement('li');
                            li.className = "p-4 hover:bg-gray-50 cursor-pointer transition flex items-start gap-3";
                            li.innerHTML = `
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <i class='bx bxs-map'></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">${lokasi.nama_lokasi}</h4>
                                    <p class="text-xs text-gray-500 line-clamp-1">${lokasi.alamat}</p>
                                </div>
                            `;
                            // Redirect to location details or public cari lokasi page
                            li.addEventListener('click', () => {
                                window.location.href = `/cari-lokasi?q=${encodeURIComponent(lokasi.nama_lokasi)}`;
                            });
                            searchList.appendChild(li);
                        });
                    }
                    searchResults.classList.remove('hidden');
                })
                .catch(error => console.error('Error fetching data:', error));
        }, 300); // 300ms debounce
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
});
</script>

@endsection
