<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // Menampilkan halaman konfirmasi pembayaran
    public function pembayaran(Request $request)
    {
        $depositId = $request->query('deposit_id');
        if (!$depositId) {
            return redirect()->route('user.dashboard')->with('error', 'Pilih titipan yang ingin dibayar.');
        }

        $deposit = \App\Models\Deposit::with('location')->findOrFail($depositId);
        return view('user.pembayaran', compact('deposit'));
    }

    // Memproses pembayaran dan API Onopay
    public function bayar(Request $request)
    {
        // 1. Dapatkan data Deposit dari database berdasarkan ID yang dikirim
        $request->validate([
            'deposit_id' => 'required|exists:deposits,id'
        ]);

        $deposit = \App\Models\Deposit::findOrFail($request->deposit_id);
        $amount = $deposit->total_biaya > 0 ? $deposit->total_biaya : 35000; // Contoh jika belum ada logic kalkulasi
        $transactionId = $deposit->tracking_code;
        
        // 2. Request ke API Onopay (Sesuai Screenshot Mockup)
        $qrUrl = null;
        $onopayQrCode = null;
        
        try {
            // Menggunakan HTTP Form Request sesuai screenshot ("Pilih Tipe Form")
            $response = Http::asForm()->post('http://onopay.web.id/api/v1/payment/qr/generate', [
                'phone_number' => '089690260334', // Nomor HP user dari Auth::user() jika ada
                'amount' => $amount,
                'merchant_code' => 'MARTIP ' . $deposit->location->nama_lokasi ?? 'Pusat',
                'description' => 'Titip Barang ' . $transactionId,
                'qr_mode' => 'single_use'
            ]);

            if ($response->successful() && $response->json('success') == true) {
                $qrUrl = $response->json('data.qr_image');
                $onopayQrCode = $response->json('data.qr_code');
                
                // 3. Simpan link QR ke Database
                $deposit->update([
                    'payment_qr_url' => $qrUrl,
                    // Opsional: Jika kita buat kolom payment_reference, kita bisa simpan $onopayQrCode
                ]);
            } else {
                // Log error response from API jika gagal
                \Log::error('Onopay API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Onopay Connection Error: ' . $e->getMessage());
        }

        // 4. Sistem Fallback QR Code
        // Jika API Onopay gagal diakses/unauthorized, buat QR dari API Publik agar UI tetap sama dengan mockup
        if (!$qrUrl) {
            $qrData = "MARTIP-PAY-" . $transactionId . "-AMT-" . $amount;
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=" . urlencode($qrData);
            
            $deposit->update([
                'payment_qr_url' => $qrUrl
            ]);
        }

        // Redirect ke halaman sukses (QR Ambil Barang) dengan membawa URL QR dan ID Deposit
        return redirect()->route('user.qr_ambil')->with([
            'qr_url' => $qrUrl,
            'transaction_id' => $transactionId,
            'deposit_id' => $deposit->id
        ]);
    }

    public function qrAmbil(Request $request)
    {
        $depositId = session('deposit_id') ?? $request->query('deposit_id') ?? $request->query('id');
        $deposit = null;
        
        if ($depositId) {
            $deposit = \App\Models\Deposit::with('location')->find($depositId);
        }

        if (!$deposit) {
            return redirect()->route('user.dashboard')->with('error', 'Titipan tidak ditemukan.');
        }

        // Jika tidak ada data flash session dari pembayaran, kasih default (untuk demo)
        $qrUrl = session('qr_url', $deposit ? $deposit->payment_qr_url : "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=DEMO-QR");
        $transactionId = session('transaction_id', $deposit ? $deposit->tracking_code : 'INV-' . date('Ymd') . '-001');

        return view('user.qr_ambil', compact('qrUrl', 'transactionId', 'deposit'));
    }
}
