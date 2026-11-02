@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="auth-wrap panel">
    <h1 class="page-title">Daftar Pelanggan</h1>
    <p class="muted">Buat akun untuk memesan lapangan olahraga.</p>
    <form method="POST" action="{{ route('register') }}" class="form" style="margin-top:1rem">
        @csrf
        <label>Nama
            <input type="text" name="nama" value="{{ old('nama') }}" required>
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>No. Telepon
            <input type="text" name="no_telepon" value="{{ old('no_telepon') }}">
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label>Konfirmasi Password
            <input type="password" name="password_confirmation" required>
        </label>
        <button class="btn" type="submit">Daftar</button>
    </form>
    <p class="muted" style="margin-top:1rem">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</div>
@endsection
