@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="page-title">Dashboard Admin</h1>
<p class="muted">Ringkasan operasional booking lapangan.</p>

<div class="stats">
    <div class="stat"><div class="label">Lapangan</div><div class="value">{{ $stats['lapangan'] }}</div></div>
    <div class="stat"><div class="label">Total Booking</div><div class="value">{{ $stats['booking'] }}</div></div>
    <div class="stat"><div class="label">Menunggu Verifikasi</div><div class="value">{{ $stats['menunggu_verifikasi'] }}</div></div>
    <div class="stat"><div class="label">Terkonfirmasi</div><div class="value">{{ $stats['terkonfirmasi'] }}</div></div>
</div>

<div class="panel">
    <div class="toolbar">
        <h2 style="margin:0">Booking Terbaru</h2>
        <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-ghost">Lihat semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Lapangan</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($terbaru as $b)
                    <tr>
                        <td>{{ $b->kode_booking }}</td>
                        <td>{{ $b->user->nama }}</td>
                        <td>{{ $b->lapangan->nama_lapangan }}</td>
                        <td>{{ str_replace('_',' ', $b->status_booking) }}</td>
                        <td>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
