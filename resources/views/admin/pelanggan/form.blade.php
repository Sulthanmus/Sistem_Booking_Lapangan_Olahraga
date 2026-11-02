@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="panel" style="max-width:640px">
    <h1 class="page-title">Edit Pelanggan</h1>
    <form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan) }}" class="form">
        @csrf @method('PUT')
        <label>Nama
            <input type="text" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required>
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required>
        </label>
        <label>No. Telepon
            <input type="text" name="no_telepon" value="{{ old('no_telepon', $pelanggan->no_telepon) }}">
        </label>
        <label>Password Baru (opsional)
            <input type="password" name="password">
        </label>
        <label>Konfirmasi Password
            <input type="password" name="password_confirmation">
        </label>
        <div class="actions">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="{{ route('admin.pelanggan.index') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
