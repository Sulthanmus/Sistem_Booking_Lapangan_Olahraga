@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Verifikasi Pembayaran</h1>
        <p class="muted">Setujui atau tolak bukti pembayaran pelanggan.</p>
    </div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Booking</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th>Jumlah</th>
                <th>Bukti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $p)
                <tr>
                    <td>{{ $p->booking->kode_booking }}</td>
                    <td>{{ $p->booking->user->nama }}</td>
                    <td>{{ str_replace('_',' ', $p->metode_pembayaran) }}</td>
                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if($p->bukti_pembayaran)
                            <a href="{{ asset('storage/'.$p->bukti_pembayaran) }}" target="_blank">Lihat</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ str_replace('_',' ', $p->status_pembayaran) }}</td>
                    <td class="actions">
                        @if($p->status_pembayaran === 'menunggu_verifikasi')
                            <form method="POST" action="{{ route('admin.pembayaran.verify', $p) }}">
                                @csrf
                                <button class="btn btn-sm" type="submit">Verifikasi</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pembayaran.reject', $p) }}">
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit">Tolak</button>
                            </form>
                        @else
                            <span class="muted">Selesai</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="muted">Belum ada pembayaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $pembayarans->links() }}</div>
@endsection
