<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - MARTIP')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons (Boxicons) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles (Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between h-full flex-shrink-0">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-gray-50 overflow-hidden">
                <a href="{{ route('landing') }}" class="block w-full py-2 h-full">
                    <img src="{{ asset('images/logo-website.png') }}" alt="Logo Martip" class="h-full w-auto object-contain scale-125 origin-left">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                @if(Auth::user()->role === 'admin')
                    <!-- Admin Links -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-home-alt text-xl'></i> Dashboard
                    </a>

                    <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Master Data</p>
                    <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.locations.*') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-map-pin text-xl'></i> Lokasi Titip
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-user text-xl'></i> User
                    </a>

                    <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Transaksi</p>
                    <a href="{{ route('admin.deposits.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.deposits.*') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-package text-xl'></i> Titipan Barang
                    </a>

                    <p class="px-4 pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Laporan</p>
                    <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.laporan') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-file text-xl'></i> Laporan Titipan
                    </a>
                @else
                    <!-- User Links -->
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('user.dashboard') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-home-alt text-xl'></i> Dashboard
                    </a>
                    <a href="{{ route('user.lokasi') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('user.lokasi*') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-map-pin text-xl'></i> Lokasi Titip
                    </a>
                    <a href="{{ route('user.tracking') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('user.tracking') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-target-lock text-xl'></i> Tracking Barang
                    </a>
                    <a href="{{ route('user.riwayat') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('user.riwayat') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-time text-xl'></i> Riwayat Titipan
                    </a>
                    <a href="{{ route('user.profil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('user.profil') ? 'bg-blue-600 text-white font-medium shadow-md shadow-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class='bx bx-user text-xl'></i> Profil Pengguna
                    </a>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 w-full transition">
                    <i class='bx bx-log-out text-xl'></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 flex-shrink-0">
            <div>
                <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class='bx bx-menu text-2xl'></i>
                </button>
            </div>
            <div class="flex items-center gap-6">
                <!-- Notification -->
                <button class="relative text-gray-500 hover:text-gray-700">
                    <i class='bx bx-bell text-2xl'></i>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                
                <!-- Profile -->
                <div class="flex items-center gap-3 border-l border-gray-200 pl-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=EBF4FF&color=2563EB" alt="Profile" class="w-10 h-10 rounded-full">
                    <div class="hidden md:block">
                        <p class="text-sm font-semibold text-gray-700 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <i class='bx bx-chevron-down text-gray-400'></i>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
        
        <!-- Footer in Content -->
        <footer class="p-6 text-center text-xs text-gray-400 border-t border-gray-100 bg-white mt-auto">
            &copy; {{ date('Y') }} MARTIP (Mari Titip Barang). All rights reserved.
        </footer>
    </div>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true,
            offset: 50,
        });
    </script>
</body>
</html>
