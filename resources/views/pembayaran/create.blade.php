@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="panel" style="max-width:640px">
    <h1 class="page-title">Pembayaran Booking</h1>
    <p class="muted">Kode: <strong>{{ $booking->kode_booking }}</strong> · Total <strong class="price">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong></p>

    @if($pembayaran && $pembayaran->status_pembayaran === 'ditolak')
        <div class="alert alert-error">Pembayaran sebelumnya ditolak. Silakan unggah ulang bukti yang valid.</div>
    @endif

    <form method="POST" action="{{ route('pembayaran.store', $booking) }}" enctype="multipart/form-data" class="form form-wide" style="margin-top:1rem">
        @csrf
        <label>Metode Pembayaran
            <select name="metode_pembayaran" required>
                <option value="transfer_bank" @selected(old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '')==='transfer_bank')>Transfer Bank</option>
                <option value="e_wallet" @selected(old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '')==='e_wallet')>E-Wallet</option>
                <option value="qris" @selected(old('metode_pembayaran', $pembayaran->metode_pembayaran ?? '')==='qris')>QRIS</option>
            </select>
        </label>
        <label>Bukti Pembayaran (gambar)
            <input type="file" name="bukti_pembayaran" accept="image/*" required>
        </label>
        <div class="actions">
            <button class="btn" type="submit">Kirim Bukti</button>
            <a class="btn btn-ghost" href="{{ route('booking.show', $booking) }}">Kembali</a>
        </div>
    </form>
</div>
@endsection
