<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Location extends Model
{
    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'deskripsi',
        'image',
        'tarif_per_hari',
    ];

    protected $appends = ['image_url'];

    /**
     * Selalu return URL gambar yang valid.
     * Jika image kosong/null, return placeholder SVG yang pasti selalu ada.
     */
    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image) && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        // Placeholder SVG inline yang selalu tersedia (tidak bergantung internet/CDN)
        return "data:image/svg+xml," . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400"><rect fill="#EEF2FF" width="800" height="400"/><g transform="translate(400,200)"><rect x="-40" y="-30" width="80" height="60" rx="8" fill="#C7D2FE" stroke="#818CF8" stroke-width="2"/><circle cx="0" cy="-8" r="10" fill="#818CF8"/><polygon points="-30,20 -10,-5 5,10 20,-2 30,20" fill="#818CF8" opacity="0.7"/><text y="55" text-anchor="middle" fill="#6366F1" font-family="system-ui,sans-serif" font-size="14" font-weight="600">Belum ada foto</text></g></svg>');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
