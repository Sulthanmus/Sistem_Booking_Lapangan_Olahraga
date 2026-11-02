@extends('layouts.app')

@section('title', $lapangan->nama_lapangan)

@section('content')
<div class="toolbar">
    <div>
        <p class="muted"><a href="{{ route('lapangan.index') }}">&larr; Kembali</a></p>
        <h1 class="page-title">{{ $lapangan->nama_lapangan }}</h1>
        <p class="muted">{{ $lapangan->jenis_lapangan }} · {{ $lapangan->lokasi }}</p>
    </div>
    <p class="price" style="font-size:1.3rem">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</p>
</div>

<div class="grid grid-2">
    <div class="panel">
        <div class="card-media" style="border-radius:12px;margin-bottom:1rem">
            @if($lapangan->foto)
                <img src="{{ asset('storage/'.$lapangan->foto) }}" alt="{{ $lapangan->nama_lapangan }}" style="border-radius:12px">
            @else
                {{ $lapangan->jenis_lapangan }}
            @endif
        </div>
        <h3>Deskripsi</h3>
        <p class="muted">{{ $lapangan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
        <h3 style="margin-top:1rem">Fasilitas</h3>
        <p class="muted">{{ $lapangan->fasilitas ?: '—' }}</p>
    </div>

    <div class="panel">
        <h2 style="margin-top:0">Jadwal Tersedia</h2>
        <div class="slot-list">
            @forelse($jadwals as $jadwal)
                <div class="slot">
                    <div>
                        <strong>{{ $jadwal->tanggal->translatedFormat('d M Y') }}</strong>
                        <div class="muted">{{ substr($jadwal->jam_mulai,0,5) }} – {{ substr($jadwal->jam_selesai,0,5) }}</div>
                    </div>
                    <div class="actions">
                        @if($jadwal->status === 'tersedia')
                            <span class="badge badge-ok">Tersedia</span>
                            <a class="btn btn-sm" href="{{ route('booking.create', ['id_jadwal' => $jadwal->id_jadwal]) }}">Booking</a>
                        @else
                            <span class="badge badge-danger">Dibooking</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="muted">Belum ada jadwal ke depan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
