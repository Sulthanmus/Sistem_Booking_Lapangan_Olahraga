<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $table = 'booking';

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'kode_booking',
        'id_user',
        'id_lapangan',
        'id_jadwal',
        'tanggal_booking',
        'total_harga',
        'status_booking',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_booking' => 'datetime',
            'total_harga' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_booking', 'id_booking');
    }

    public static function generateKode(): string
    {
        do {
            $kode = 'BK'.now()->format('ymd').strtoupper(Str::random(4));
        } while (self::where('kode_booking', $kode)->exists());

        return $kode;
    }

    public function dapatDibatalkan(): bool
    {
        return in_array($this->status_booking, ['menunggu_pembayaran', 'menunggu_verifikasi'], true);
    }
}
