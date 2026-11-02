@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Laporan Pemilik</h1>
        <p class="muted">Ringkasan booking, transaksi, dan pendapatan.</p>
    </div>
    <form class="filters" method="GET">
        <label>Dari
            <input type="date" name="dari" value="{{ $dari }}">
        </label>
        <label>Sampai
            <input type="date" name="sampai" value="{{ $sampai }}">
        </label>
        <button class="btn btn-sm" type="submit">Terapkan</button>
    </form>
</div>

<div class="stats">
    <div class="stat"><div class="label">Total Booking</div><div class="value">{{ $bookings->count() }}</div></div>
    <div class="stat"><div class="label">Transaksi Lunas</div><div class="value">{{ $transaksi->count() }}</div></div>
    <div class="stat"><div class="label">Pendapatan</div><div class="value" style="font-size:1.2rem">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div></div>
    <div class="stat"><div class="label">Terkonfirmasi</div><div class="value">{{ $perStatus['terkonfirmasi'] ?? 0 }}</div></div>
</div>

<div class="grid grid-2 section">
    <div class="panel">
        <h2 style="margin-top:0">Status Booking</h2>
        <ul>
            @forelse($perStatus as $status => $total)
                <li>{{ str_replace('_',' ', $status) }}: <strong>{{ $total }}</strong></li>
            @empty
                <li class="muted">Tidak ada data.</li>
            @endforelse
        </ul>
    </div>
    <div class="panel">
        <h2 style="margin-top:0">Pendapatan per Lapangan</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Lapangan</th><th>Jumlah</th><th>Pendapatan</th></tr>
                </thead>
                <tbody>
                    @forelse($perLapangan as $row)
                        <tr>
                            <td>{{ $row->nama_lapangan }}</td>
                            <td>{{ $row->jumlah }}</td>
                            <td>Rp {{ number_format($row->pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted">Belum ada pendapatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel section">
    <h2 style="margin-top:0">Transaksi Terverifikasi</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Booking</th>
                    <th>Pelanggan</th>
                    <th>Lapangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                    <tr>
                        <td>{{ optional($t->tanggal_pembayaran)->format('d/m/Y H:i') }}</td>
                        <td>{{ $t->booking->kode_booking }}</td>
                        <td>{{ $t->booking->user->nama }}</td>
                        <td>{{ $t->booking->lapangan->nama_lapangan }}</td>
                        <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel section">
    <h2 style="margin-top:0">Daftar Booking</h2>
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
                @forelse($bookings as $b)
                    <tr>
                        <td>{{ $b->kode_booking }}</td>
                        <td>{{ $b->user->nama }}</td>
                        <td>{{ $b->lapangan->nama_lapangan }}</td>
                        <td>{{ str_replace('_',' ', $b->status_booking) }}</td>
                        <td>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Belum ada booking pada rentang ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
