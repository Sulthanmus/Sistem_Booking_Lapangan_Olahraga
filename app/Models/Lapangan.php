<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lapangan extends Model
{
    protected $table = 'lapangan';

    protected $primaryKey = 'id_lapangan';

    protected $fillable = [
        'nama_lapangan',
        'jenis_lapangan',
        'harga_per_jam',
        'lokasi',
        'fasilitas',
        'deskripsi',
        'foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga_per_jam' => 'decimal:2',
        ];
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_lapangan', 'id_lapangan');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'id_lapangan', 'id_lapangan');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
