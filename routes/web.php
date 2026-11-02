<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Pelanggan
    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
        Route::get('/lapangan/{lapangan}', [LapanganController::class, 'show'])->name('lapangan.show');

        Route::get('/booking/buat', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking-saya', [BookingController::class, 'mine'])->name('booking.mine');
        Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::post('/booking/{booking}/batalkan', [BookingController::class, 'cancel'])->name('booking.cancel');

        Route::get('/pembayaran/{booking}', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran/{booking}', [PembayaranController::class, 'store'])->name('pembayaran.store');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [LapanganController::class, 'adminDashboard'])->name('dashboard');

        Route::get('/lapangan', [LapanganController::class, 'adminIndex'])->name('lapangan.index');
        Route::get('/lapangan/create', [LapanganController::class, 'create'])->name('lapangan.create');
        Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');
        Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('lapangan.edit');
        Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('lapangan.update');
        Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');

        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

        Route::get('/booking', [BookingController::class, 'adminIndex'])->name('booking.index');
        Route::post('/booking/{booking}/batalkan', [BookingController::class, 'cancel'])->name('booking.cancel');

        Route::get('/pembayaran', [PembayaranController::class, 'adminIndex'])->name('pembayaran.index');
        Route::post('/pembayaran/{pembayaran}/verifikasi', [PembayaranController::class, 'verify'])->name('pembayaran.verify');
        Route::post('/pembayaran/{pembayaran}/tolak', [PembayaranController::class, 'reject'])->name('pembayaran.reject');

        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
        Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    });

    // Pemilik
    Route::middleware('role:pemilik')->prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    });
});
