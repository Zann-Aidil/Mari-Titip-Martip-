<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
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

    // Memproses pembayaran dan API Onopay (legacy - redirect ke halaman QR)
    public function bayar(Request $request)
    {
        $request->validate([
            'deposit_id' => 'required|exists:deposits,id'
        ]);

        $deposit       = \App\Models\Deposit::findOrFail($request->deposit_id);
        $amount        = $deposit->total_biaya > 0 ? $deposit->total_biaya : 35000;
        $transactionId = $deposit->tracking_code;

        $qrUrl        = null;
        $onopayQrCode = null;

        try {
            $response = Http::asForm()->post('https://onopay.web.id/api/v1/payment/qr/generate', [
                'phone_number'  => config('onopay.merchant_phone'),
                'amount'        => $amount,
                'merchant_code' => 'MARTIP ' . $deposit->location->nama_lokasi ?? 'Pusat',
                'description'   => 'Titip Barang ' . $transactionId,
                'qr_mode'       => 'single_use'
            ]);

            if ($response->successful() && $response->json('success') == true) {
                $qrUrl        = $response->json('data.qr_image');
                $onopayQrCode = $response->json('data.qr_code');

                $deposit->update([
                    'payment_qr_url'  => $qrUrl,
                    'payment_qr_code' => $onopayQrCode,
                ]);
            } else {
                \Log::error('Onopay API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Onopay Connection Error: ' . $e->getMessage());
        }

        if (!$qrUrl) {
            $qrData = json_encode([
                'tracking_code' => $transactionId,
                'invoice_id'    => $deposit->id
            ]);
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=" . urlencode($qrData);

            $deposit->update(['payment_qr_url' => $qrUrl]);
        }

        return redirect()->route('user.qr_ambil')->with([
            'qr_url'        => $qrUrl,
            'transaction_id' => $transactionId,
            'deposit_id'    => $deposit->id
        ]);
    }

    public function qrAmbil(Request $request)
    {
        $depositId = session('deposit_id') ?? $request->query('deposit_id') ?? $request->query('id');
        $deposit   = null;

        if ($depositId) {
            $deposit = \App\Models\Deposit::with('location')->find($depositId);
        }

        if (!$deposit) {
            return redirect()->route('user.dashboard')->with('error', 'Titipan tidak ditemukan.');
        }

        $qrUrl         = session('qr_url', $deposit ? $deposit->payment_qr_url : "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=DEMO-QR");
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
            'status' => strtolower($deposit->payment_status)
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

        if ($request->qr_code) {
            $onopay = new \App\Services\OnopayService();

            // Gunakan nomor HP user yang terdaftar di website — tercatat di dashboard Onopay sebagai PAYER
            $payerPhone = $request->payer_phone
                       ?? Auth::user()?->phone
                       ?? null;

            if (!$payerPhone) {
                \Log::warning('mobilePay: payer_phone kosong, transaksi Onopay dilewati');
            } else {
                $response = $onopay->pay($request->qr_code, $payerPhone);

                if (isset($response['success']) && $response['success']) {
                    try {
                        \App\Models\OnopayTransaction::create([
                            'transaction_id' => $response['data']['transaction_id'] ?? null,
                            'amount'         => $deposit->total_biaya,
                            'payer_phone'    => $payerPhone,
                            'receiver_phone' => config('onopay.merchant_phone'),
                            'status'         => 'success',
                            'qr_code'        => $request->qr_code,
                            'type'           => 'qr_payment'
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning('Gagal simpan OnopayTransaction: ' . $e->getMessage());
                    }
                } else {
                    \Log::warning('Onopay pay API gagal untuk user ' . $payerPhone . ': ' . ($response['message'] ?? 'unknown'));
                }
            }
        }

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
     * Cek saldo Onopay user yang sedang login.
     * Menggunakan nomor HP yang didaftarkan saat daftar di MARTIP.
     */
    public function checkUserBalance(Request $request)
    {
        $user  = Auth::user();
        $phone = $user->phone ?? null;

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP tidak ditemukan di profil kamu. Silakan update profil terlebih dahulu.'
            ], 400);
        }

        $onopay   = new \App\Services\OnopayService();
        $response = $onopay->checkBalance($phone);

        // Sertakan nomor HP di response agar Vue bisa tampilkan
        if (isset($response['success'])) {
            $response['payer_phone'] = $phone;
        }

        return response()->json($response);
    }

    /**
     * Proses pembayaran REAL dalam satu langkah:
     * 1. Generate QR dari akun merchant Onopay (082213993917)
     * 2. Langsung bayar menggunakan saldo Onopay milik user (nomor HP dari registrasi)
     * → Saldo user terpotong & tercatat di dashboard Onopay Admin
     */
    public function processPayment(Request $request)
    {
        $request->validate(['deposit_id' => 'required|exists:deposits,id']);

        $deposit    = \App\Models\Deposit::with('location')->findOrFail($request->deposit_id);
        $user       = Auth::user();
        $amount     = $deposit->total_biaya > 0 ? $deposit->total_biaya : 35000;
        $payerPhone = $user->phone ?? null;

        if (!$payerPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP tidak ditemukan. Silakan update profil terlebih dahulu.'
            ], 400);
        }

        $onopay = new \App\Services\OnopayService();

        // STEP 1: Generate QR Code dari akun merchant MARTIP
        $qrResponse = $onopay->generateQR(
            config('onopay.merchant_phone'),
            $amount,
            'MARTIP',
            'Titip Barang ' . $deposit->tracking_code
        );

        if (!isset($qrResponse['success']) || !$qrResponse['success']) {
            \Log::error('processPayment: gagal generate QR', $qrResponse);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR pembayaran: ' . ($qrResponse['message'] ?? 'Coba lagi.')
            ], 400);
        }

        $qrCode  = $qrResponse['data']['qr_code']  ?? null;
        $qrImage = $qrResponse['data']['qr_image'] ?? null;

        $deposit->update([
            'payment_qr_url'  => $qrImage,
            'payment_qr_code' => $qrCode,
        ]);

        // STEP 2: Potong saldo Onopay nomor HP user yang daftar di MARTIP
        // → Ini yang muncul di dashboard Onopay Admin sebagai transaksi user
        $payResponse = $onopay->pay($qrCode, $payerPhone);

        if (!isset($payResponse['success']) || !$payResponse['success']) {
            \Log::warning('processPayment: pay gagal untuk ' . $payerPhone, $payResponse);
            return response()->json([
                'success' => false,
                'message' => $payResponse['message']
                    ?? 'Pembayaran gagal. Pastikan nomor ' . $payerPhone . ' terdaftar di Onopay dan saldo mencukupi.'
            ], 400);
        }

        // STEP 3: Simpan histori transaksi
        try {
            \App\Models\OnopayTransaction::create([
                'transaction_id' => $payResponse['data']['transaction_id'] ?? null,
                'amount'         => $amount,
                'payer_phone'    => $payerPhone,
                'receiver_phone' => config('onopay.merchant_phone'),
                'status'         => 'success',
                'qr_code'        => $qrCode,
                'type'           => 'qr_payment'
            ]);
        } catch (\Exception $e) {
            \Log::warning('processPayment: gagal simpan transaksi: ' . $e->getMessage());
        }

        // STEP 4: Tandai deposit sebagai LUNAS
        $deposit->update([
            'payment_status' => 'paid',
            'status'         => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil! Saldo Onopay ' . $payerPhone . ' telah terpotong.',
            'data'    => [
                'transaction_id' => $payResponse['data']['transaction_id'] ?? null,
                'receipt_no'     => $deposit->tracking_code,
                'amount'         => $amount,
                'payer_phone'    => $payerPhone,
                'merchant'       => 'MARTIP ' . ($deposit->location->nama_lokasi ?? 'Pusat'),
                'item_name'      => $deposit->nama_barang,
                'date'           => now()->format('Y-m-d H:i:s'),
                'status'         => 'LUNAS'
            ]
        ]);
    }

    /**
     * Generate QR Code via backend (proxy ke Onopay) — dipanggil dari Vue
     * Menghindari CORS issue jika dipanggil langsung dari browser
     */
    public function generateQrApi(Request $request)
    {
        $request->validate(['deposit_id' => 'required|exists:deposits,id']);

        $deposit = \App\Models\Deposit::with('location')->findOrFail($request->deposit_id);
        $amount  = $deposit->total_biaya > 0 ? $deposit->total_biaya : 35000;

        $onopay   = new \App\Services\OnopayService();
        $response = $onopay->generateQR(
            config('onopay.merchant_phone'),
            $amount,
            'MARTIP',
            'Titip Barang ' . $deposit->tracking_code
        );

        if (isset($response['success']) && $response['success']) {
            $deposit->update([
                'payment_qr_url'  => $response['data']['qr_image'] ?? null,
                'payment_qr_code' => $response['data']['qr_code']  ?? null,
            ]);

            return response()->json([
                'success'  => true,
                'qr_image' => $response['data']['qr_image'] ?? null,
                'qr_code'  => $response['data']['qr_code']  ?? null,
            ]);
        }

        // Fallback: Jika Onopay API tidak tersedia, pakai QR publik agar UI tetap berjalan
        $qrData     = urlencode(json_encode(['tracking_code' => $deposit->tracking_code, 'id' => $deposit->id]));
        $fallbackQr = "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={$qrData}";

        return response()->json([
            'success'  => false,
            'qr_image' => $fallbackQr,
            'qr_code'  => null,
            'message'  => $response['message'] ?? 'Onopay tidak tersedia, QR fallback aktif'
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

        $deposit = \App\Models\Deposit::where('payment_qr_code', $qrCode)->first();

        if ($deposit) {
            $deposit->update(['payment_status' => 'paid']);
            \Log::info('Onopay Webhook: deposit ' . $deposit->tracking_code . ' marked as paid');
        }

        \App\Models\OnopayTransaction::where('qr_code', $qrCode)->update(['status' => 'success']);

        return response()->json(['message' => 'ok']);
    }
}
