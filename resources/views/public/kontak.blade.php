@extends('layouts.public')

@section('title', 'Kontak - MARTIP')

@section('content')
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Punya pertanyaan atau butuh bantuan? Tim dukungan kami siap membantu Anda 24/7.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-2xl shrink-0"><i class='bx bx-map'></i></div>
                    <div>
                        <h3 class="font-bold text-gray-900">Kantor Pusat</h3>
                        <p class="text-sm text-gray-600 mt-1">Jl. Jendral Sudirman No. 123, Jakarta Selatan, 12190</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-2xl shrink-0"><i class='bx bxl-whatsapp'></i></div>
                    <div>
                        <h3 class="font-bold text-gray-900">WhatsApp</h3>
                        <p class="text-sm text-gray-600 mt-1">+62 812 3456 7890<br><span class="text-xs text-green-600 font-bold">Fast Response</span></p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 text-2xl shrink-0"><i class='bx bx-envelope'></i></div>
                    <div>
                        <h3 class="font-bold text-gray-900">Email</h3>
                        <p class="text-sm text-gray-600 mt-1">support@martip.web.id</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pesan Anda</label>
                        <textarea rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm"></textarea>
                    </div>
                    <button type="button" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-blue-700 transition shadow-md shadow-blue-200">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
