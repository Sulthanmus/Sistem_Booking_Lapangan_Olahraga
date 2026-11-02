@extends('layouts.app')

@section('title', $jadwal->exists ? 'Edit Jadwal' : 'Tambah Jadwal')

@section('content')
<div class="panel" style="max-width:640px">
    <h1 class="page-title">{{ $jadwal->exists ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h1>
    <form method="POST"
          action="{{ $jadwal->exists ? route('admin.jadwal.update', $jadwal) : route('admin.jadwal.store') }}"
          class="form">
        @csrf
        @if($jadwal->exists) @method('PUT') @endif

        <label>Lapangan
            <select name="id_lapangan" required>
                @foreach($lapangans as $l)
                    <option value="{{ $l->id_lapangan }}" @selected(old('id_lapangan', $jadwal->id_lapangan)==$l->id_lapangan)>{{ $l->nama_lapangan }}</option>
                @endforeach
            </select>
        </label>
        <label>Tanggal
            <input type="date" name="tanggal" value="{{ old('tanggal', optional($jadwal->tanggal)->format('Y-m-d')) }}" required>
        </label>
        <label>Jam Mulai
            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai ? substr($jadwal->jam_mulai,0,5) : '') }}" required>
        </label>
        <label>Jam Selesai
            <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai ? substr($jadwal->jam_selesai,0,5) : '') }}" required>
        </label>
        <label>Status
            <select name="status" required>
                <option value="tersedia" @selected(old('status', $jadwal->status ?? 'tersedia')==='tersedia')>Tersedia</option>
                <option value="dibooking" @selected(old('status', $jadwal->status)==='dibooking')>Dibooking</option>
            </select>
        </label>
        <div class="actions">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="{{ route('admin.jadwal.index') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
