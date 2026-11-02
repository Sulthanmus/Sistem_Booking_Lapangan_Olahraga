@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<section class="hero">
    <h1>Booking Lapangan</h1>
    <p>Pesan lapangan futsal, badminton, basket, dan tenis dengan mudah. Lihat jadwal tersedia, booking, lalu unggah bukti pembayaran.</p>
    <div class="actions">
        <a href="{{ route('register') }}" class="btn">Daftar Sekarang</a>
        <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
    </div>
</section>

<section class="section">
    <div class="toolbar">
        <h2 class="page-title" style="margin:0">Daftar Lapangan</h2>
        <form class="filters" method="GET">
            <label>Cari
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama lapangan">
            </label>
            <label>Jenis
                <select name="jenis">
                    <option value="">Semua</option>
                    @foreach(['Futsal','Badminton','Basket','Tenis'] as $jenis)
                        <option value="{{ $jenis }}" @selected(request('jenis') === $jenis)>{{ $jenis }}</option>
                    @endforeach
                </select>
            </label>
            <button class="btn btn-sm" type="submit">Filter</button>
        </form>
    </div>

    <div class="grid grid-3">
        @forelse($lapangans as $lapangan)
            <article class="card">
                <div class="card-media">
                    @if($lapangan->foto)
                        <img src="{{ asset('storage/'.$lapangan->foto) }}" alt="{{ $lapangan->nama_lapangan }}">
                    @else
                        {{ $lapangan->jenis_lapangan }}
                    @endif
                </div>
                <div class="card-body">
                    <h3>{{ $lapangan->nama_lapangan }}</h3>
                    <p>{{ $lapangan->lokasi }}</p>
                    <p class="price">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</p>
                    <a class="btn btn-sm" href="{{ route('login') }}">Masuk untuk booking</a>
                </div>
            </article>
        @empty
            <p class="muted">Belum ada lapangan aktif.</p>
        @endforelse
    </div>
</section>
@endsection
