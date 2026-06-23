<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MARTIP - Mari Titip Barang')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons (Boxicons) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles (Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="border-b border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('landing') }}" class="flex items-center h-full py-2">
                    <img src="{{ asset('images/logo-website.png') }}" alt="Logo Martip" class="h-full w-auto object-contain scale-125 origin-left">
                </a>

                @if(request()->routeIs('login') || request()->routeIs('register'))
                    <div class="hidden md:flex items-center">
                        <a href="{{ route('landing') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-blue-600 font-medium hover:bg-blue-50 transition flex items-center gap-2">
                            <i class='bx bx-arrow-back'></i> Kembali ke Beranda
                        </a>
                    </div>
                @else
                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('landing') }}" class="text-blue-600 font-medium">Beranda</a>
                        <a href="{{ route('cari.lokasi') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Cari Lokasi</a>
                        <a href="{{ route('tentang.kami') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Tentang Kami</a>
                        <a href="{{ route('kontak') }}" class="text-gray-600 hover:text-blue-600 font-medium transition">Kontak</a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition flex items-center gap-2">
                            <i class='bx bx-user'></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition flex items-center gap-2 shadow-md shadow-blue-200">
                            <i class='bx bx-user-plus'></i> Daftar Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} MARTIP (Mari Titip Barang). All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-gray-900">Kebijakan Privasi</a>
                <a href="#" class="hover:text-gray-900">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-gray-900">Bantuan</a>
            </div>
        </div>
    </footer>
    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
        });
    </script>
</body>
</html>
