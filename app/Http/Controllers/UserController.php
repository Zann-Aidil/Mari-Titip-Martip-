<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class UserController extends Controller
{
    public function dashboard() { 
        $locations = Location::take(4)->get();
        $deposits = \App\Models\Deposit::where('user_id', auth()->id() ?? 1)
                    ->with('location')
                    ->latest()
                    ->take(3)
                    ->get();
        
        $activeCount = \App\Models\Deposit::where('user_id', auth()->id() ?? 1)->where('status', 'accepted')->count();
        $pendingCount = \App\Models\Deposit::where('user_id', auth()->id() ?? 1)->where('status', 'pending')->count();
        $completedCount = \App\Models\Deposit::where('user_id', auth()->id() ?? 1)->where('status', 'retrieved')->count();
        
        return view('user.dashboard', compact('locations', 'deposits', 'activeCount', 'pendingCount', 'completedCount')); 
    }
    public function lokasi() { 
        $locations = Location::all();
        return view('user.lokasi', compact('locations')); 
    }
    public function detailLokasi($id) { 
        $lokasi = Location::findOrFail($id);
        return view('user.detail_lokasi', compact('lokasi')); 
    }
    public function formTitipan($id) { 
        $location = Location::findOrFail($id);
        return view('user.form_titipan', compact('location')); 
    }
    
    public function storeTitipan(Request $request, $id) { 
        $request->validate([
            'nama_barang' => 'required|string',
            'jumlah_barang' => 'required|integer',
            'durasi' => 'required',
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $location = Location::findOrFail($id);
        $totalBiaya = $location->tarif_per_hari * $request->durasi;

        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $file = $request->file('item_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan ke storage/app/public/deposits
            $imagePath = $file->storeAs('deposits', $filename, 'public');
        }

        $deposit = \App\Models\Deposit::create([
            'user_id' => auth()->id() ?? 1, // Fallback ke user 1 jika tidak login/testing
            'location_id' => $location->id,
            'nama_barang' => $request->nama_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'durasi' => $request->durasi,
            'catatan' => $request->catatan,
            'foto_barang' => $imagePath, // Save the path to database
            'total_biaya' => $totalBiaya,
            'tracking_code' => 'INV-' . strtoupper(uniqid()),
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        // Redirect to payment with deposit_id
        return redirect()->route('user.pembayaran', ['deposit_id' => $deposit->id]);
    }
    public function tracking($id = null) { 
        $query = \App\Models\Deposit::where('user_id', auth()->id() ?? 1)->with('location');
        if ($id) {
            $deposit = $query->findOrFail($id);
        } else {
            // Get latest active deposit
            $deposit = $query->whereIn('status', ['pending', 'accepted'])->latest()->first() 
                       ?? $query->latest()->first();
        }

        if (!$deposit) {
            return redirect()->route('user.dashboard')->with('error', 'Belum ada titipan.');
        }

        return view('user.tracking', compact('deposit')); 
    }
    public function riwayat() { 
        $deposits = \App\Models\Deposit::where('user_id', auth()->id() ?? 1)
            ->with('location')
            ->latest()
            ->paginate(10);
        return view('user.riwayat', compact('deposits')); 
    }
    public function profil() { return view('user.profil'); }
}
