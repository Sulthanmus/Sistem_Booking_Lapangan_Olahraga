<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $bookings = Booking::with(['user', 'lapangan', 'jadwal', 'pembayaran'])
            ->whereDate('tanggal_booking', '>=', $dari)
            ->whereDate('tanggal_booking', '<=', $sampai)
            ->latest('tanggal_booking')
            ->get();

        $transaksi = Pembayaran::with(['booking.user', 'booking.lapangan'])
            ->where('status_pembayaran', 'diverifikasi')
            ->whereDate('tanggal_pembayaran', '>=', $dari)
            ->whereDate('tanggal_pembayaran', '<=', $sampai)
            ->latest('tanggal_pembayaran')
            ->get();

        $pendapatan = $transaksi->sum('jumlah');

        $perStatus = Booking::select('status_booking', DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal_booking', '>=', $dari)
            ->whereDate('tanggal_booking', '<=', $sampai)
            ->groupBy('status_booking')
            ->pluck('total', 'status_booking');

        $perLapangan = Booking::query()
            ->join('lapangan', 'booking.id_lapangan', '=', 'lapangan.id_lapangan')
            ->where('booking.status_booking', 'terkonfirmasi')
            ->whereDate('booking.tanggal_booking', '>=', $dari)
            ->whereDate('booking.tanggal_booking', '<=', $sampai)
            ->select('lapangan.nama_lapangan', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(booking.total_harga) as pendapatan'))
            ->groupBy('lapangan.nama_lapangan')
            ->orderByDesc('pendapatan')
            ->get();

        return view('pemilik.laporan', compact(
            'dari',
            'sampai',
            'bookings',
            'transaksi',
            'pendapatan',
            'perStatus',
            'perLapangan'
        ));
    }
}
