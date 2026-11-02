<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Booking Lapangan') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="{{ route('home') }}">Booking Lapangan</a>
            <nav class="nav-links">
                @auth
                    @if(auth()->user()->isPelanggan())
                        <a href="{{ route('lapangan.index') }}">Lapangan</a>
                        <a href="{{ route('booking.mine') }}">Booking Saya</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <a href="{{ route('admin.lapangan.index') }}">Lapangan</a>
                        <a href="{{ route('admin.jadwal.index') }}">Jadwal</a>
                        <a href="{{ route('admin.booking.index') }}">Booking</a>
                        <a href="{{ route('admin.pembayaran.index') }}">Pembayaran</a>
                        <a href="{{ route('admin.pelanggan.index') }}">Pelanggan</a>
                    @endif
                    @if(auth()->user()->isPemilik())
                        <a href="{{ route('pemilik.laporan') }}">Laporan</a>
                    @endif
                    <span class="nav-user">{{ auth()->user()->nama }} ({{ auth()->user()->role }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container main">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Sistem Booking Lapangan Olahraga</p>
        </div>
    </footer>
</body>
</html>
