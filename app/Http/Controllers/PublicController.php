<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function landing() { return view('public.landing'); }
    public function cariLokasi() { 
        $locations = \App\Models\Location::all();
        return view('public.cari_lokasi', compact('locations')); 
    }
    public function tentangKami() { return view('public.tentang_kami'); }
    public function kontak()
    {
        return view('public.kontak');
    }

    public function searchApi(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }

        $locations = \App\Models\Location::where('nama_lokasi', 'LIKE', "%{$query}%")
                                        ->orWhere('alamat', 'LIKE', "%{$query}%")
                                        ->take(5)
                                        ->get();

        return response()->json($locations);
    }
}
