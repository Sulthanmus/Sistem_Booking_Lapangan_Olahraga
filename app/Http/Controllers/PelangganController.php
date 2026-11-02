<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelanggans = User::where('role', 'pelanggan')
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($inner) use ($request) {
                    $inner->where('nama', 'like', '%'.$request->q.'%')
                        ->orWhere('email', 'like', '%'.$request->q.'%');
                });
            })
            ->withCount('bookings')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function show(User $pelanggan)
    {
        abort_unless($pelanggan->role === 'pelanggan', 404);

        $pelanggan->load(['bookings' => fn ($q) => $q->with(['lapangan', 'jadwal'])->latest('tanggal_booking')]);

        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit(User $pelanggan)
    {
        abort_unless($pelanggan->role === 'pelanggan', 404);

        return view('admin.pelanggan.form', compact('pelanggan'));
    }

    public function update(Request $request, User $pelanggan)
    {
        abort_unless($pelanggan->role === 'pelanggan', 404);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($pelanggan->id)],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $pelanggan->nama = $data['nama'];
        $pelanggan->email = $data['email'];
        $pelanggan->no_telepon = $data['no_telepon'] ?? null;

        if (! empty($data['password'])) {
            $pelanggan->password = Hash::make($data['password']);
        }

        $pelanggan->save();

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(User $pelanggan)
    {
        abort_unless($pelanggan->role === 'pelanggan', 404);

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
