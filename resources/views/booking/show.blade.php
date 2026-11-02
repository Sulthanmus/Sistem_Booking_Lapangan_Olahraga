@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="toolbar">
    <div>
        <p class="muted"><a href="{{ auth()->user()->isPelanggan() ? route('booking.mine') : route('admin.booking.index') }}">&larr; Kembali</a></p>
        <h1 class="page-title">{{ $booking->kode_booking }}</h1>
    </div>
    <span class="badge badge-info">{{ str_replace('_',' ', $booking->status_booking) }}</span>
</div>

<div class="grid grid-2">
    <div class="panel stack">
        <div><strong>Pelanggan</strong><div class="muted">{{ $booking->user->nama }}</div></div>
        <div><strong>Lapangan</strong><div class="muted">{{ $booking->lapangan->nama_lapangan }}</div></div>
        <div><strong>Tanggal Main</strong><div class="muted">{{ $booking->jadwal->tanggal->translatedFormat('d M Y') }}, {{ substr($booking->jadwal->jam_mulai,0,5) }}–{{ substr($booking->jadwal->jam_selesai,0,5) }}</div></div>
        <div><strong>Tanggal Booking</strong><div class="muted">{{ $booking->tanggal_booking->format('d/m/Y H:i') }}</div></div>
        <div><strong>Total</strong><div class="price">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div></div>
    </div>
    <div class="panel stack">
        <h3 style="margin:0">Pembayaran</h3>
        @if($booking->pembayaran)
            <div><strong>Metode</strong><div class="muted">{{ str_replace('_',' ', $booking->pembayaran->metode_pembayaran) }}</div></div>
            <div><strong>Status</strong><div class="muted">{{ str_replace('_',' ', $booking->pembayaran->status_pembayaran) }}</div></div>
            @if($booking->pembayaran->bukti_pembayaran)
                <div>
                    <strong>Bukti</strong><br>
                    <a href="{{ asset('storage/'.$booking->pembayaran->bukti_pembayaran) }}" target="_blank">
                        <img class="thumb" src="{{ asset('storage/'.$booking->pembayaran->bukti_pembayaran) }}" alt="Bukti">
                    </a>
                </div>
            @endif
        @else
            <p class="muted">Belum ada pembayaran.</p>
        @endif

        <div class="actions">
            @if(auth()->user()->isPelanggan() && in_array($booking->status_booking, ['menunggu_pembayaran','menunggu_verifikasi'], true))
                <a class="btn" href="{{ route('pembayaran.create', $booking) }}">Unggah / Ubah Bukti</a>
            @endif
            @if($booking->dapatDibatalkan() && (auth()->user()->isPelanggan() || auth()->user()->isAdmin()))
                <form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.booking.cancel', $booking) : route('booking.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking?')">
                    @csrf
                    <button class="btn btn-danger" type="submit">Batalkan</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
