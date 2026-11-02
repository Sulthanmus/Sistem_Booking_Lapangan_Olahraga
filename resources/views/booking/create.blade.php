@extends('layouts.app')

@section('title', 'Konfirmasi Booking')

@section('content')
<div class="panel" style="max-width:640px">
    <h1 class="page-title">Konfirmasi Booking</h1>
    <p class="muted">Periksa detail sebelum membuat booking.</p>

    <dl class="stack" style="margin:1.25rem 0">
        <div><strong>Lapangan</strong><div class="muted">{{ $jadwal->lapangan->nama_lapangan }}</div></div>
        <div><strong>Tanggal</strong><div class="muted">{{ $jadwal->tanggal->translatedFormat('d M Y') }}</div></div>
        <div><strong>Jam</strong><div class="muted">{{ substr($jadwal->jam_mulai,0,5) }} – {{ substr($jadwal->jam_selesai,0,5) }} ({{ number_format($jadwal->durasiJam(), 1) }} jam)</div></div>
        <div><strong>Total Harga</strong><div class="price">Rp {{ number_format($total, 0, ',', '.') }}</div></div>
    </dl>

    <form method="POST" action="{{ route('booking.store') }}" class="actions">
        @csrf
        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
        <button type="submit" class="btn">Buat Booking</button>
        <a href="{{ route('lapangan.show', $jadwal->id_lapangan) }}" class="btn btn-ghost">Batal</a>
    </form>
</div>
@endsection
