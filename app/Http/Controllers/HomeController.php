<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'pemilik' => redirect()->route('pemilik.laporan'),
                default => redirect()->route('lapangan.index'),
            };
        }

        $lapangans = Lapangan::aktif()
            ->when($request->jenis, fn ($q) => $q->where('jenis_lapangan', $request->jenis))
            ->when($request->q, fn ($q) => $q->where('nama_lapangan', 'like', '%'.$request->q.'%'))
            ->orderBy('nama_lapangan')
            ->get();

        return view('home', compact('lapangans'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pemilik' => redirect()->route('pemilik.laporan'),
            default => redirect()->route('lapangan.index'),
        };
    }
}
