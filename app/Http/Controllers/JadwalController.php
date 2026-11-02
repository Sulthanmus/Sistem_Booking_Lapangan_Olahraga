<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with('lapangan')
            ->when($request->id_lapangan, fn ($q) => $q->where('id_lapangan', $request->id_lapangan))
            ->when($request->tanggal, fn ($q) => $q->whereDate('tanggal', $request->tanggal))
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(20)
            ->withQueryString();

        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        return view('admin.jadwal.index', compact('jadwals', 'lapangans'));
    }

    public function create()
    {
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        return view('admin.jadwal.form', [
            'jadwal' => new Jadwal,
            'lapangans' => $lapangans,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Jadwal::create($data);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();

        return view('admin.jadwal.form', compact('jadwal', 'lapangans'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        if ($jadwal->status === 'dibooking' && $request->status === 'tersedia' && $jadwal->booking) {
            return back()->with('error', 'Jadwal sudah dibooking. Batalkan booking terlebih dahulu.');
        }

        $data = $this->validated($request);
        $jadwal->update($data);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        if ($jadwal->status === 'dibooking') {
            return back()->with('error', 'Tidak dapat menghapus jadwal yang sudah dibooking.');
        }

        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'id_lapangan' => ['required', 'exists:lapangan,id_lapangan'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'jam_selesai' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/', 'after:jam_mulai'],
            'status' => ['required', 'in:tersedia,dibooking'],
        ]);

        $data['jam_mulai'] = substr($data['jam_mulai'], 0, 5);
        $data['jam_selesai'] = substr($data['jam_selesai'], 0, 5);

        return $data;
    }
}
