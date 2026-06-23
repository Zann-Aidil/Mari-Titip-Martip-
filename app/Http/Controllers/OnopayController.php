<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OnopayService;
use App\Models\OnopayTransaction;

class OnopayController extends Controller
{
    protected $onopayService;

    public function __construct(OnopayService $onopayService)
    {
        $this->onopayService = $onopayService;
    }

    public function checkUser(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $response = $this->onopayService->checkUser($request->phone_number);
        return response()->json($response, isset($response['success']) && $response['success'] ? 200 : 400);
    }

    public function checkBalance(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $response = $this->onopayService->checkBalance($request->phone_number);
        return response()->json($response, isset($response['success']) && $response['success'] ? 200 : 400);
    }

    public function topup(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'amount' => 'required|numeric|min:1000',
        ]);

        $response = $this->onopayService->topup($request->phone_number, $request->amount);

        if (isset($response['success']) && $response['success']) {
            OnopayTransaction::create([
                'transaction_id' => $response['data']['transaction_id'] ?? null,
                'amount' => $request->amount,
                'receiver_phone' => $request->phone_number,
                'status' => 'success',
                'type' => 'topup'
            ]);
        }

        return response()->json($response, isset($response['success']) && $response['success'] ? 200 : 400);
    }

    public function generateQR(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'amount' => 'required|numeric|min:100',
            'merchant_code' => 'nullable|string',
            'description' => 'nullable|string',
            'qr_mode' => 'nullable|string',
        ]);

        $response = $this->onopayService->generateQR(
            $request->phone_number,
            $request->amount,
            $request->merchant_code,
            $request->description,
            $request->qr_mode ?? 'single_use'
        );

        if (isset($response['success']) && $response['success']) {
            OnopayTransaction::create([
                'amount' => $request->amount,
                'receiver_phone' => $request->phone_number,
                'status' => 'pending',
                'qr_code' => $response['data']['qr_code'] ?? null,
                'type' => 'qr_payment'
            ]);
        }

        return response()->json($response, isset($response['success']) && $response['success'] ? 200 : 400);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'payer_phone' => 'required|string',
        ]);

        $response = $this->onopayService->pay($request->qr_code, $request->payer_phone);

        if (isset($response['success']) && $response['success']) {
            $transaction = OnopayTransaction::where('qr_code', $request->qr_code)->first();
            if ($transaction) {
                $transaction->update([
                    'transaction_id' => $response['data']['transaction_id'] ?? null,
                    'payer_phone' => $request->payer_phone,
                    'status' => 'success'
                ]);
            } else {
                OnopayTransaction::create([
                    'transaction_id' => $response['data']['transaction_id'] ?? null,
                    'amount' => $response['data']['amount'] ?? 0,
                    'payer_phone' => $request->payer_phone,
                    'receiver_phone' => $response['data']['receiver_phone'] ?? null,
                    'status' => 'success',
                    'qr_code' => $request->qr_code,
                    'type' => 'qr_payment'
                ]);
            }
        }

        return response()->json($response, isset($response['success']) && $response['success'] ? 200 : 400);
    }

    public function getTransactions()
    {
        $transactions = OnopayTransaction::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function getTransactionDetail($id)
    {
        $transaction = OnopayTransaction::find($id);
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
}