@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="auth-wrap panel">
    <h1 class="page-title">Masuk</h1>
    <p class="muted">Masuk untuk mengelola booking lapangan Anda.</p>
    <form method="POST" action="{{ route('login') }}" class="form" style="margin-top:1rem">
        @csrf
        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label style="display:flex;align-items:center;gap:.5rem;font-weight:500">
            <input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya
        </label>
        <button class="btn" type="submit">Masuk</button>
    </form>
    <p class="muted" style="margin-top:1rem">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai pelanggan</a></p>
</div>
@endsection
