@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Booking Saya</h1>
        <p class="muted">Status, riwayat, dan pembatalan booking.</p>
    </div>
    <form class="filters" method="GET">
        <label>Status
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                @foreach(['menunggu_pembayaran','menunggu_verifikasi','terkonfirmasi','dibatalkan'] as $status)
                    <option value="{{ $status }}" @selected(request('status')===$status)>{{ str_replace('_',' ', $status) }}</option>
                @endforeach
            </select>
        </label>
    </form>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Lapangan</th>
                <th>Jadwal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->kode_booking }}</td>
                    <td>{{ $booking->lapangan->nama_lapangan }}</td>
                    <td>
                        {{ $booking->jadwal->tanggal->format('d/m/Y') }}<br>
                        <span class="muted">{{ substr($booking->jadwal->jam_mulai,0,5) }}–{{ substr($booking->jadwal->jam_selesai,0,5) }}</span>
                    </td>
                    <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @php $s = $booking->status_booking; @endphp
                        <span class="badge {{ $s==='terkonfirmasi'?'badge-ok':($s==='dibatalkan'?'badge-danger':($s==='menunggu_verifikasi'?'badge-info':'badge-warn')) }}">
                            {{ str_replace('_',' ', $s) }}
                        </span>
                    </td>
                    <td class="actions">
                        <a class="btn btn-sm btn-ghost" href="{{ route('booking.show', $booking) }}">Detail</a>
                        @if($booking->status_booking === 'menunggu_pembayaran' || ($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'ditolak'))
                            <a class="btn btn-sm" href="{{ route('pembayaran.create', $booking) }}">Bayar</a>
                        @endif
                        @if($booking->dapatDibatalkan())
                            <form method="POST" action="{{ route('booking.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking ini?')">
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit">Batalkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Belum ada booking.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $bookings->links() }}</div>
@endsection
