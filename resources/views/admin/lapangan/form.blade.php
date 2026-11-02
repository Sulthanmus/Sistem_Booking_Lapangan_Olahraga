@extends('layouts.app')

@section('title', $lapangan->exists ? 'Edit Lapangan' : 'Tambah Lapangan')

@section('content')
<div class="panel" style="max-width:720px">
    <h1 class="page-title">{{ $lapangan->exists ? 'Edit Lapangan' : 'Tambah Lapangan' }}</h1>
    <form method="POST"
          action="{{ $lapangan->exists ? route('admin.lapangan.update', $lapangan) : route('admin.lapangan.store') }}"
          enctype="multipart/form-data"
          class="form form-wide">
        @csrf
        @if($lapangan->exists) @method('PUT') @endif

        <label>Nama Lapangan
            <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required>
        </label>
        <label>Jenis
            <select name="jenis_lapangan" required>
                @foreach(['Futsal','Badminton','Basket','Tenis'] as $jenis)
                    <option value="{{ $jenis }}" @selected(old('jenis_lapangan', $lapangan->jenis_lapangan)===$jenis)>{{ $jenis }}</option>
                @endforeach
            </select>
        </label>
        <label>Harga per Jam
            <input type="number" name="harga_per_jam" min="0" step="1000" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" required>
        </label>
        <label>Lokasi
            <input type="text" name="lokasi" value="{{ old('lokasi', $lapangan->lokasi) }}" required>
        </label>
        <label>Fasilitas
            <textarea name="fasilitas">{{ old('fasilitas', $lapangan->fasilitas) }}</textarea>
        </label>
        <label>Deskripsi
            <textarea name="deskripsi">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
        </label>
        <label>Foto
            <input type="file" name="foto" accept="image/*">
        </label>
        @if($lapangan->foto)
            <img class="thumb" src="{{ asset('storage/'.$lapangan->foto) }}" alt="Foto lapangan">
        @endif
        <label>Status
            <select name="status" required>
                <option value="aktif" @selected(old('status', $lapangan->status ?? 'aktif')==='aktif')>Aktif</option>
                <option value="nonaktif" @selected(old('status', $lapangan->status)==='nonaktif')>Nonaktif</option>
            </select>
        </label>
        <div class="actions">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="{{ route('admin.lapangan.index') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
