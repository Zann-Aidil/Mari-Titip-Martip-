<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;
use App\Models\Deposit;

class AdminController extends Controller
{
    public function dashboard() { 
        $totalTitipan = \App\Models\Deposit::count();
        $titipanTiba = \App\Models\Deposit::where('status', 'accepted')->count();
        $dalamPengambilan = \App\Models\Deposit::where('status', 'pending')->count();
        $selesai = \App\Models\Deposit::where('status', 'retrieved')->count();
        
        $titipanTerbaru = \App\Models\Deposit::with(['user', 'location'])->latest()->take(5)->get();
        $lokasiTeraktif = \App\Models\Location::withCount('deposits')->orderByDesc('deposits_count')->take(4)->get();
        
        return view('admin.dashboard', compact(
            'totalTitipan', 'titipanTiba', 'dalamPengambilan', 'selesai',
            'titipanTerbaru', 'lokasiTeraktif'
        )); 
    }
    public function dataUser() { 
        $users = \App\Models\User::latest()->paginate(10);
        return view('admin.data_user', compact('users')); 
    }
    public function dataLokasi() { 
        $locations = \App\Models\Location::latest()->paginate(10);
        return view('admin.data_lokasi', compact('locations')); 
    }
    public function dataTitipan() { 
        $deposits = \App\Models\Deposit::with(['user', 'location'])->latest()->paginate(10);
        return view('admin.data_titipan', compact('deposits')); 
    }
    public function laporan(Request $request) { 
        $query = \App\Models\Deposit::with(['user', 'location']);

        if($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $deposits = $query->latest()->paginate(20);

        $totalPendapatan = \App\Models\Deposit::where('status', 'retrieved')->sum('total_biaya');
        $totalTitipan = \App\Models\Deposit::count();
        $titipanAktif = \App\Models\Deposit::whereIn('status', ['pending', 'accepted'])->count();

        return view('admin.laporan', compact('deposits', 'totalPendapatan', 'totalTitipan', 'titipanAktif')); 
    }

    public function exportPdf() {
        $deposits = \App\Models\Deposit::with(['user', 'location'])->get();
        $totalPendapatan = $deposits->where('status', 'retrieved')->sum('total_biaya');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('
            <h1>Laporan Titipan MARTIP</h1>
            <p>Total Pendapatan Bersih: Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</p>
            <table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-size: 12px; border-collapse: collapse;">
                <tr style="background:#f3f4f6;">
                    <th>Kode</th><th>User</th><th>Lokasi</th><th>Barang</th><th>Status</th><th>Total Biaya</th><th>Tanggal</th>
                </tr>
                ' . $deposits->map(function($d) {
                    return "<tr>
                        <td>{$d->tracking_code}</td>
                        <td>{$d->user->name}</td>
                        <td>{$d->location->nama_lokasi}</td>
                        <td>{$d->nama_barang} ({$d->jumlah_barang} item)</td>
                        <td>{$d->status}</td>
                        <td>Rp " . number_format($d->total_biaya, 0, ',', '.') . "</td>
                        <td>{$d->created_at->format('d M Y H:i')}</td>
                    </tr>";
                })->implode('') . '
            </table>
        ');
        return $pdf->download('laporan_titipan_martip.pdf');
    }

    public function exportCsv() {
        $deposits = \App\Models\Deposit::with(['user', 'location'])->get();
        $csvData = "Kode,User,Lokasi,Barang,Status,Total Biaya,Tanggal\n";
        foreach ($deposits as $d) {
            $csvData .= "{$d->tracking_code},{$d->user->name},{$d->location->nama_lokasi},{$d->nama_barang},{$d->status},{$d->total_biaya},{$d->created_at->format('Y-m-d H:i:s')}\n";
        }
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="laporan_titipan_martip.csv"');
    }

    // --- CRUD Lokasi ---
    public function storeLokasi(Request $request) {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tarif_per_hari' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $data = $request->only(['nama_lokasi', 'alamat', 'tarif_per_hari']);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['image'] = $file->storeAs('locations', $filename, 'public');
        }
        
        \App\Models\Location::create($data);
        return redirect()->route('admin.locations.index')->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function updateLokasi(Request $request, $id) {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tarif_per_hari' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $location = \App\Models\Location::findOrFail($id);
        $data = $request->only(['nama_lokasi', 'alamat', 'tarif_per_hari']);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['image'] = $file->storeAs('locations', $filename, 'public');
        }
        
        $location->update($data);
        return redirect()->route('admin.locations.index')->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroyLokasi($id) {
        $location = \App\Models\Location::findOrFail($id);
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Lokasi berhasil dihapus');
    }

    // --- CRUD User ---
    public function storeUser(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user'
        ]);
        
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,user'
        ]);
        
        $user = \App\Models\User::findOrFail($id);
        $data = $request->only(['name', 'email', 'role']);
        
        if($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroyUser($id) {
        $user = \App\Models\User::findOrFail($id);
        // Jangan hapus diri sendiri (Admin)
        if ($user->id !== auth()->id()) {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
        }
        return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri');
    }

    // --- CRUD Deposit (Update Status) ---
    public function updateDepositStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:pending,accepted,retrieved'
        ]);
        $deposit = \App\Models\Deposit::findOrFail($id);
        $deposit->update(['status' => $request->status]);
        return redirect()->route('admin.deposits.index')->with('success', 'Status titipan berhasil diperbarui');
    }
}
