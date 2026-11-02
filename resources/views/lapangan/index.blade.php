@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Daftar Lapangan</h1>
        <p class="muted">Pilih lapangan dan lihat jadwal yang tersedia.</p>
    </div>
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
                <p>{{ $lapangan->lokasi }} · {{ $lapangan->jenis_lapangan }}</p>
                <p class="price">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</p>
                <a class="btn btn-sm" href="{{ route('lapangan.show', $lapangan) }}">Lihat Detail & Jadwal</a>
            </div>
        </article>
    @empty
        <p class="muted">Tidak ada lapangan ditemukan.</p>
    @endforelse
</div>
@endsection
