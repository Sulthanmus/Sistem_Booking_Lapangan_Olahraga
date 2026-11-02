<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $lapangans = Lapangan::aktif()
            ->when($request->jenis, fn ($q) => $q->where('jenis_lapangan', $request->jenis))
            ->when($request->q, fn ($q) => $q->where('nama_lapangan', 'like', '%'.$request->q.'%'))
            ->orderBy('nama_lapangan')
            ->get();

        return view('lapangan.index', compact('lapangans'));
    }

    public function show(Lapangan $lapangan)
    {
        abort_if($lapangan->status !== 'aktif' && ! auth()->user()?->isAdmin(), 404);

        $jadwals = $lapangan->jadwals()
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('lapangan.show', compact('lapangan', 'jadwals'));
    }

    public function adminIndex()
    {
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.form', ['lapangan' => new Lapangan]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        Lapangan::create($data);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.form', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            if ($lapangan->foto) {
                Storage::disk('public')->delete($lapangan->foto);
            }
            $data['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        $lapangan->update($data);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->foto) {
            Storage::disk('public')->delete($lapangan->foto);
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }

    public function adminDashboard()
    {
        $stats = [
            'lapangan' => Lapangan::count(),
            'booking' => Booking::count(),
            'menunggu_verifikasi' => Booking::where('status_booking', 'menunggu_verifikasi')->count(),
            'terkonfirmasi' => Booking::where('status_booking', 'terkonfirmasi')->count(),
        ];

        $terbaru = Booking::with(['user', 'lapangan', 'jadwal'])
            ->latest('tanggal_booking')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'terbaru'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_lapangan' => ['required', 'string', 'max:100'],
            'jenis_lapangan' => ['required', 'string', 'max:50'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'lokasi' => ['required', 'string', 'max:150'],
            'fasilitas' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);
    }
}
