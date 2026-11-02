<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'id_jadwal' => ['required', 'exists:jadwal,id_jadwal'],
        ]);

        $jadwal = Jadwal::with('lapangan')->findOrFail($request->id_jadwal);

        if ($jadwal->status !== 'tersedia' || $jadwal->lapangan->status !== 'aktif') {
            return redirect()->route('lapangan.show', $jadwal->id_lapangan)
                ->with('error', 'Jadwal tidak tersedia untuk dibooking.');
        }

        $total = round($jadwal->lapangan->harga_per_jam * $jadwal->durasiJam(), 2);

        return view('booking.create', compact('jadwal', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => ['required', 'exists:jadwal,id_jadwal'],
        ]);

        try {
            $booking = DB::transaction(function () use ($request) {
                $jadwal = Jadwal::with('lapangan')->lockForUpdate()->findOrFail($request->id_jadwal);

                if ($jadwal->status !== 'tersedia') {
                    throw new \RuntimeException('Jadwal sudah tidak tersedia.');
                }

                if ($jadwal->lapangan->status !== 'aktif') {
                    throw new \RuntimeException('Lapangan tidak aktif.');
                }

                $total = round($jadwal->lapangan->harga_per_jam * $jadwal->durasiJam(), 2);

                $booking = Booking::create([
                    'kode_booking' => Booking::generateKode(),
                    'id_user' => Auth::id(),
                    'id_lapangan' => $jadwal->id_lapangan,
                    'id_jadwal' => $jadwal->id_jadwal,
                    'tanggal_booking' => now(),
                    'total_harga' => $total,
                    'status_booking' => 'menunggu_pembayaran',
                ]);

                $jadwal->update(['status' => 'dibooking']);

                return $booking;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('pembayaran.create', $booking->id_booking)
            ->with('success', 'Booking dibuat. Silakan lakukan pembayaran.');
    }

    public function mine(Request $request)
    {
        $bookings = Booking::with(['lapangan', 'jadwal', 'pembayaran'])
            ->where('id_user', Auth::id())
            ->when($request->status, fn ($q) => $q->where('status_booking', $request->status))
            ->latest('tanggal_booking')
            ->paginate(10)
            ->withQueryString();

        return view('booking.mine', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $this->authorizeOwnerOrStaff($booking);
        $booking->load(['lapangan', 'jadwal', 'pembayaran', 'user']);

        return view('booking.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->id_user === Auth::id() || Auth::user()->isAdmin(), 403);

        if (! $booking->dapatDibatalkan()) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status_booking' => 'dibatalkan']);
            $booking->jadwal()->update(['status' => 'tersedia']);

            if ($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'menunggu_verifikasi') {
                $booking->pembayaran->update(['status_pembayaran' => 'ditolak']);
            }
        });

        $redirect = Auth::user()->isAdmin()
            ? route('admin.booking.index')
            : route('booking.mine');

        return redirect($redirect)->with('success', 'Booking berhasil dibatalkan.');
    }

    public function adminIndex(Request $request)
    {
        $bookings = Booking::with(['user', 'lapangan', 'jadwal', 'pembayaran'])
            ->when($request->status, fn ($q) => $q->where('status_booking', $request->status))
            ->latest('tanggal_booking')
            ->paginate(15)
            ->withQueryString();

        return view('admin.booking.index', compact('bookings'));
    }

    private function authorizeOwnerOrStaff(Booking $booking): void
    {
        $user = Auth::user();
        if ($booking->id_user !== $user->id && ! $user->isAdmin() && ! $user->isPemilik()) {
            abort(403);
        }
    }
}
