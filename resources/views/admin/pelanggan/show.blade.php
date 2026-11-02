@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<p class="muted"><a href="{{ route('admin.pelanggan.index') }}">&larr; Kembali</a></p>
<h1 class="page-title">{{ $pelanggan->nama }}</h1>
<div class="panel stack" style="margin-bottom:1rem">
    <div><strong>Email</strong><div class="muted">{{ $pelanggan->email }}</div></div>
    <div><strong>Telepon</strong><div class="muted">{{ $pelanggan->no_telepon ?: '—' }}</div></div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Lapangan</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggan->bookings as $b)
                <tr>
                    <td>{{ $b->kode_booking }}</td>
                    <td>{{ $b->lapangan->nama_lapangan }}</td>
                    <td>{{ $b->jadwal->tanggal->format('d/m/Y') }} {{ substr($b->jadwal->jam_mulai,0,5) }}</td>
                    <td>{{ str_replace('_',' ', $b->status_booking) }}</td>
                    <td>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Belum ada booking.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
