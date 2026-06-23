<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'deskripsi',
        'image',
        'tarif_per_hari',
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
