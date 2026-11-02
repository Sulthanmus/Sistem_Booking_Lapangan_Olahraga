<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function create(Booking $booking)
    {
        abort_unless($booking->id_user === Auth::id(), 403);

        if (! in_array($booking->status_booking, ['menunggu_pembayaran', 'menunggu_verifikasi'], true)) {
            return redirect()->route('booking.show', $booking->id_booking)
                ->with('error', 'Pembayaran tidak diperlukan untuk status booking ini.');
        }

        $pembayaran = $booking->pembayaran;

        if ($pembayaran && $pembayaran->status_pembayaran === 'diverifikasi') {
            return redirect()->route('booking.show', $booking->id_booking);
        }

        return view('pembayaran.create', compact('booking', 'pembayaran'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_unless($booking->id_user === Auth::id(), 403);

        if (! in_array($booking->status_booking, ['menunggu_pembayaran', 'menunggu_verifikasi'], true)) {
            return back()->with('error', 'Tidak dapat mengunggah pembayaran untuk booking ini.');
        }

        $data = $request->validate([
            'metode_pembayaran' => ['required', 'in:transfer_bank,e_wallet,qris'],
            'bukti_pembayaran' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti', 'public');

        DB::transaction(function () use ($booking, $data, $path) {
            $existing = $booking->pembayaran;

            if ($existing) {
                if ($existing->bukti_pembayaran) {
                    Storage::disk('public')->delete($existing->bukti_pembayaran);
                }
                $existing->update([
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'jumlah' => $booking->total_harga,
                    'tanggal_pembayaran' => now(),
                    'bukti_pembayaran' => $path,
                    'status_pembayaran' => 'menunggu_verifikasi',
                ]);
            } else {
                Pembayaran::create([
                    'id_booking' => $booking->id_booking,
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'jumlah' => $booking->total_harga,
                    'tanggal_pembayaran' => now(),
                    'bukti_pembayaran' => $path,
                    'status_pembayaran' => 'menunggu_verifikasi',
                ]);
            }

            $booking->update(['status_booking' => 'menunggu_verifikasi']);
        });

        return redirect()->route('booking.show', $booking->id_booking)
            ->with('success', 'Bukti pembayaran diunggah. Menunggu verifikasi admin.');
    }

    public function adminIndex()
    {
        $pembayarans = Pembayaran::with(['booking.user', 'booking.lapangan'])
            ->latest('tanggal_pembayaran')
            ->paginate(15);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verify(Pembayaran $pembayaran)
    {
        if ($pembayaran->status_pembayaran !== 'menunggu_verifikasi') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        DB::transaction(function () use ($pembayaran) {
            $pembayaran->update(['status_pembayaran' => 'diverifikasi']);
            $pembayaran->booking->update(['status_booking' => 'terkonfirmasi']);
        });

        return back()->with('success', 'Pembayaran diverifikasi. Booking terkonfirmasi.');
    }

    public function reject(Pembayaran $pembayaran)
    {
        if ($pembayaran->status_pembayaran !== 'menunggu_verifikasi') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        DB::transaction(function () use ($pembayaran) {
            $pembayaran->update(['status_pembayaran' => 'ditolak']);
            $pembayaran->booking->update(['status_booking' => 'menunggu_pembayaran']);
        });

        return back()->with('success', 'Pembayaran ditolak. Pelanggan dapat mengunggah ulang bukti.');
    }
}
