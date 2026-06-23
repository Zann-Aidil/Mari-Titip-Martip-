<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnopayTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
        'payer_phone',
        'receiver_phone',
        'status',
        'qr_code',
        'type', // e.g., 'qr_payment', 'topup'
    ];
}