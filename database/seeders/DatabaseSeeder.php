<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'nama' => 'Rina Amelia',
            'email' => 'admin@booking.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'pemilik@booking.com',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
            'no_telepon' => '081298765432',
        ]);

        $andi = User::create([
            'nama' => 'Andi Pratama',
            'email' => 'andi@email.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'no_telepon' => '081111111111',
        ]);

        $siti = User::create([
            'nama' => 'Siti Rahma',
            'email' => 'siti@email.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'no_telepon' => '082222222222',
        ]);

        User::create([
            'nama' => 'Doni Wijaya',
            'email' => 'doni@email.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'no_telepon' => '083333333333',
        ]);

        $lapangans = [
            [
                'nama_lapangan' => 'Futsal A',
                'jenis_lapangan' => 'Futsal',
                'harga_per_jam' => 150000,
                'lokasi' => 'Gedung Olahraga Lt.1',
                'fasilitas' => 'Rumput sintetis, lampu LED, tribune',
                'deskripsi' => 'Lapangan futsal indoor ukuran standar.',
                'status' => 'aktif',
            ],
            [
                'nama_lapangan' => 'Badminton 1',
                'jenis_lapangan' => 'Badminton',
                'harga_per_jam' => 80000,
                'lokasi' => 'Gedung Olahraga Lt.2',
                'fasilitas' => 'AC, net standar, lantai vinyl',
                'deskripsi' => 'Lapangan badminton dengan pencahayaan baik.',
                'status' => 'aktif',
            ],
            [
                'nama_lapangan' => 'Basket A',
                'jenis_lapangan' => 'Basket',
                'harga_per_jam' => 200000,
                'lokasi' => 'Outdoor Area Timur',
                'fasilitas' => 'Ring adjustable, lantai beton, bangku penonton',
                'deskripsi' => 'Lapangan basket outdoor.',
                'status' => 'aktif',
            ],
            [
                'nama_lapangan' => 'Tenis 1',
                'jenis_lapangan' => 'Tenis',
                'harga_per_jam' => 175000,
                'lokasi' => 'Area Tenis Barat',
                'fasilitas' => 'Hard court, net baru, lampu malam',
                'deskripsi' => 'Lapangan tenis hard court.',
                'status' => 'aktif',
            ],
            [
                'nama_lapangan' => 'Futsal B',
                'jenis_lapangan' => 'Futsal',
                'harga_per_jam' => 140000,
                'lokasi' => 'Gedung Olahraga Lt.1',
                'fasilitas' => 'Sedang dalam perawatan',
                'deskripsi' => 'Lapangan futsal B sedang perawatan.',
                'status' => 'nonaktif',
            ],
        ];

        $createdLapangan = [];
        foreach ($lapangans as $data) {
            $createdLapangan[] = Lapangan::create($data);
        }

        $aktif = collect($createdLapangan)->where('status', 'aktif')->values();
        $slots = [
            ['08:00', '09:00'],
            ['09:00', '10:00'],
            ['10:00', '11:00'],
            ['16:00', '17:00'],
            ['17:00', '18:00'],
            ['18:00', '19:00'],
            ['19:00', '20:00'],
            ['20:00', '21:00'],
        ];

        $dates = ['2026-11-03', '2026-11-04', '2026-11-05', '2026-11-06', '2026-11-07'];
        $jadwalList = [];

        foreach ($aktif as $lapangan) {
            foreach ($dates as $date) {
                foreach (array_slice($slots, 0, 5) as [$mulai, $selesai]) {
                    $jadwalList[] = Jadwal::create([
                        'id_lapangan' => $lapangan->id_lapangan,
                        'tanggal' => $date,
                        'jam_mulai' => $mulai,
                        'jam_selesai' => $selesai,
                        'status' => 'tersedia',
                    ]);
                }
            }
        }

        // Sample booking + payment for demo
        $jadwalBooked = $jadwalList[0];
        $jadwalBooked->update(['status' => 'dibooking']);

        $booking = Booking::create([
            'kode_booking' => 'BK261103DEMO',
            'id_user' => $andi->id,
            'id_lapangan' => $jadwalBooked->id_lapangan,
            'id_jadwal' => $jadwalBooked->id_jadwal,
            'tanggal_booking' => now(),
            'total_harga' => $jadwalBooked->lapangan->harga_per_jam,
            'status_booking' => 'menunggu_verifikasi',
        ]);

        Pembayaran::create([
            'id_booking' => $booking->id_booking,
            'metode_pembayaran' => 'transfer_bank',
            'jumlah' => $booking->total_harga,
            'tanggal_pembayaran' => now(),
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        $jadwalConfirmed = $jadwalList[10];
        $jadwalConfirmed->update(['status' => 'dibooking']);

        $booking2 = Booking::create([
            'kode_booking' => 'BK261104OK01',
            'id_user' => $siti->id,
            'id_lapangan' => $jadwalConfirmed->id_lapangan,
            'id_jadwal' => $jadwalConfirmed->id_jadwal,
            'tanggal_booking' => now()->subDay(),
            'total_harga' => $jadwalConfirmed->lapangan->harga_per_jam,
            'status_booking' => 'terkonfirmasi',
        ]);

        Pembayaran::create([
            'id_booking' => $booking2->id_booking,
            'metode_pembayaran' => 'qris',
            'jumlah' => $booking2->total_harga,
            'tanggal_pembayaran' => now()->subDay(),
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'diverifikasi',
        ]);

        unset($admin);
    }
}
