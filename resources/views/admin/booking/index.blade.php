@extends('layouts.app')

@section('title', 'Kelola Booking')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Kelola Booking</h1>
        <p class="muted">Pantau dan batalkan booking pelanggan.</p>
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
                <th>Pelanggan</th>
                <th>Lapangan / Jadwal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->kode_booking }}</td>
                    <td>{{ $booking->user->nama }}</td>
                    <td>
                        {{ $booking->lapangan->nama_lapangan }}<br>
                        <span class="muted">{{ $booking->jadwal->tanggal->format('d/m/Y') }} {{ substr($booking->jadwal->jam_mulai,0,5) }}–{{ substr($booking->jadwal->jam_selesai,0,5) }}</span>
                    </td>
                    <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td>{{ str_replace('_',' ', $booking->status_booking) }}</td>
                    <td class="actions">
                        @if($booking->dapatDibatalkan())
                            <form method="POST" action="{{ route('admin.booking.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking?')">
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit">Batalkan</button>
                            </form>
                        @else
                            <span class="muted">—</span>
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
