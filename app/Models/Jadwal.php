<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_lapangan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'id_jadwal', 'id_jadwal');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function durasiJam(): float
    {
        $date = $this->tanggal?->format('Y-m-d') ?? now()->toDateString();
        $mulai = Carbon::parse($date.' '.$this->jam_mulai);
        $selesai = Carbon::parse($date.' '.$this->jam_selesai);
        $minutes = abs($selesai->diffInMinutes($mulai));

        return max($minutes / 60, 0.5);
    }

}
