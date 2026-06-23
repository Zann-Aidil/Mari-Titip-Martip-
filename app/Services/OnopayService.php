<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OnopayService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('onopay.base_url', 'https://onopay.web.id/api/v1');
    }

    public function checkUser($phoneNumber)
    {
        return $this->request('POST', '/merchant/check-user', [
            'phone_number' => $phoneNumber
        ]);
    }

    public function checkBalance($phoneNumber)
    {
        return $this->request('POST', '/merchant/check-balance', [
            'phone_number' => $phoneNumber
        ]);
    }

    public function topup($phoneNumber, $amount)
    {
        return $this->request('POST', '/payment/topup', [
            'phone_number' => $phoneNumber,
            'amount' => $amount
        ]);
    }

    public function generateQR($phoneNumber, $amount, $merchantCode = null, $description = null, $qrMode = 'single_use')
    {
        $payload = [
            'phone_number' => $phoneNumber,
            'amount' => $amount,
            'qr_mode' => $qrMode
        ];

        if ($merchantCode) $payload['merchant_code'] = $merchantCode;
        if ($description) $payload['description'] = $description;

        return $this->request('POST', '/payment/qr/generate', $payload);
    }

    public function pay($qrCode, $payerPhone)
    {
        return $this->request('POST', '/payment/qr/pay', [
            'qr_code' => $qrCode,
            'payer_phone' => $payerPhone
        ]);
    }

    protected function request($method, $endpoint, $data = [])
    {
        try {
            $url = $this->baseUrl . $endpoint;
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->$method($url, $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('OnoPay API Error: ' . $e->getMessage(), ['endpoint' => $endpoint, 'data' => $data]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungi server OnoPay: ' . $e->getMessage()
            ];
        }
    }
}
