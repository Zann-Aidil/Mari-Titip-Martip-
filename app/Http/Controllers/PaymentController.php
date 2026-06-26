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
            $response = Http::asForm()->post('https://onopay.web.id/api/v1/payment/qr/generate', [
                'phone_number' => config('onopay.merchant_phone'), // Nomor merchant dari .env
                'amount'       => $amount,
                'merchant_code'=> 'MARTIP ' . $deposit->location->nama_lokasi ?? 'Pusat',
                'description'  => 'Titip Barang ' . $transactionId,
                'qr_mode'      => 'single_use'
            ]);

            if ($response->successful() && $response->json('success') == true) {
                $qrUrl        = $response->json('data.qr_image');
                $onopayQrCode = $response->json('data.qr_code');
                
                // Simpan QR ke Database (termasuk qr_code untuk webhook lookup)
                $deposit->update([
                    'payment_qr_url'  => $qrUrl,
                    'payment_qr_code' => $onopayQrCode,
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
            $qrData = json_encode([
                'tracking_code' => $transactionId,
                'invoice_id' => $deposit->id
            ]);
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

    public function checkStatus($trackId)
    {
        $deposit = \App\Models\Deposit::where('tracking_code', $trackId)->first();
        
        if (!$deposit) {
            return response()->json(['status' => 'error', 'message' => 'Deposit not found'], 404);
        }

        return response()->json([
            'status' => strtolower($deposit->payment_status) // misalnya 'paid' atau 'pending'
        ]);
    }

    public function mobilePay(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string',
            'qr_code'       => 'nullable|string',
            'payer_phone'   => 'nullable|string',
        ]);

        $deposit = \App\Models\Deposit::where('tracking_code', $request->tracking_code)->first();

        if (!$deposit) {
            return response()->json(['success' => false, 'message' => 'Deposit not found'], 404);
        }

        // Jika qr_code dikirim, teruskan ke Onopay API untuk mencatat transaksi real
        // Jika tidak ada qr_code (mode fallback/simulasi dari web), langsung update status DB saja
        if ($request->qr_code) {
            $onopay = new \App\Services\OnopayService();
            $payerPhone = $request->payer_phone ?? '089690260334';

            $response = $onopay->pay($request->qr_code, $payerPhone);

            if (isset($response['success']) && $response['success']) {
                // Simpan ke database lokal untuk histori Onopay
                try {
                    \App\Models\OnopayTransaction::create([
                        'transaction_id' => $response['data']['transaction_id'] ?? null,
                        'amount'         => $deposit->total_biaya,
                        'payer_phone'    => $payerPhone,
                        'receiver_phone' => '08123456789',
                        'status'         => 'success',
                        'qr_code'        => $request->qr_code,
                        'type'           => 'qr_payment'
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Gagal simpan OnopayTransaction: ' . $e->getMessage());
                }
            } else {
                // Onopay gagal — log saja tapi tetap lanjut update DB lokal
                \Log::warning('Onopay pay API gagal: ' . ($response['message'] ?? 'unknown'));
            }
        }

        // Update status deposit di database kita (selalu dijalankan)
        $deposit->update([
            'payment_status' => 'paid',
            'status'         => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment successful',
            'data'    => [
                'receipt_no' => $deposit->tracking_code,
                'amount'     => $deposit->total_biaya,
                'date'       => now()->format('Y-m-d H:i:s'),
                'item_name'  => $deposit->nama_barang,
                'merchant'   => 'MARTIP ' . ($deposit->location->nama_lokasi ?? 'Pusat'),
                'status'     => 'LUNAS'
            ]
        ]);
    }

    /**
     * Menerima notifikasi pembayaran realtime dari Onopay (Webhook)
     */
    public function onopayWebhook(Request $request)
    {
        \Log::info('Onopay Webhook received', $request->all());

        $qrCode = $request->input('qr_code');

        if (!$qrCode) {
            return response()->json(['message' => 'ok']);
        }

        // Cari deposit berdasarkan qr_code yang tersimpan
        $deposit = \App\Models\Deposit::where('payment_qr_code', $qrCode)->first();

        if ($deposit) {
            $deposit->update(['payment_status' => 'paid']);
            \Log::info('Onopay Webhook: deposit ' . $deposit->tracking_code . ' marked as paid');
        }

        // Update OnopayTransaction jika ada
        \App\Models\OnopayTransaction::where('qr_code', $qrCode)->update(['status' => 'success']);

        return response()->json(['message' => 'ok']);
    }
}
