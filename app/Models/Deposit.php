<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id',
        'location_id',
        'nama_barang',
        'jumlah_barang',
        'durasi',
        'catatan',
        'foto_barang',
        'total_biaya',
        'payment_status',
        'payment_qr_url',
        'tracking_code',
        'status',
        'waktu_titip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
