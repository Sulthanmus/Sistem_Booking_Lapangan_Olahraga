# Sistem Booking Lapangan Olahraga

Aplikasi web Laravel 12 untuk pemesanan lapangan olahraga (futsal, badminton, basket, tenis) sesuai SKPL/DPPL.

## Peran

| Role | Akses |
|------|--------|
| **pelanggan** | Lihat lapangan & jadwal, booking, bayar + upload bukti, status/riwayat, batalkan |
| **admin** | CRUD lapangan & jadwal, kelola booking, verifikasi pembayaran, kelola pelanggan |
| **pemilik** | Laporan booking, transaksi, pendapatan |

## Persyaratan

- PHP 8.2+ + Composer
- MySQL via **Laragon** (atau XAMPP), database `db_booking_lapangan`

> Di mesin pengembangan saat ini PHP terdeteksi dari XAMPP (`C:\xampp\php\php.exe`). Konfigurasi `.env` MySQL tetap cocok untuk Laragon (host `127.0.0.1`, user `root`, password kosong).

## Setup

### 1. Database MySQL (Laragon)

1. Buka Laragon → Start All (Apache/Nginx + MySQL).
2. Buat database lewat HeidiSQL / phpMyAdmin:

```sql
CREATE DATABASE db_booking_lapangan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau CLI (sesuaikan path Laragon):

```bash
C:\laragon\bin\mysql\mysql-8.x.x\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS db_booking_lapangan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Environment

File `.env` sudah dikonfigurasi untuk MySQL:

```
APP_NAME="Booking Lapangan"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_booking_lapangan
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
CACHE_STORE=file
FILESYSTEM_DISK=public
```

Jika password root Laragon tidak kosong, isi `DB_PASSWORD`.

### 3. Install & migrate

Di terminal Laragon / folder proyek:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 4. Jalankan server

**Opsi A — `artisan serve`:**

```bash
php artisan serve
```

**Opsi B — virtual host Laragon:** taruh/symlink proyek ke `C:\laragon\www\Sistem_Booking_Lapangan` lalu buka URL Laragon.

Buka: [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Akun default (password: `password`)

| Email | Role | Nama |
|-------|------|------|
| `admin@booking.com` | admin | Rina Amelia |
| `pemilik@booking.com` | pemilik | Budi Santoso |
| `andi@email.com` | pelanggan | Andi Pratama |
| `siti@email.com` | pelanggan | Siti Rahma |
| `doni@email.com` | pelanggan | Doni Wijaya |

## Fitur (RF-01 s/d RF-16)

1. Registrasi pelanggan  
2. Login  
3. Logout  
4. Daftar lapangan  
5. Detail lapangan + jadwal  
6. Buat booking (jadwal `tersedia` → `dibooking`)  
7. Pembayaran + upload bukti  
8. Status booking pelanggan  
9. Riwayat booking  
10. Batalkan booking (jadwal kembali `tersedia`)  
11. Admin CRUD lapangan  
12. Admin CRUD jadwal  
13. Admin kelola booking  
14. Admin verifikasi pembayaran  
15. Admin kelola pelanggan  
16. Pemilik laporan booking / transaksi / pendapatan  

## Struktur penting

- Controllers: `AuthController`, `LapanganController`, `JadwalController`, `BookingController`, `PembayaranController`, `PelangganController`, `LaporanController`, `HomeController`
- Middleware alias: `role` (`App\Http\Middleware\RoleMiddleware`)
- Upload: `storage/app/public/bukti` dan `storage/app/public/lapangan`

## Troubleshooting

- **SQLSTATE connection refused**: pastikan MySQL XAMPP aktif.
- **Gambar bukti/foto tidak tampil**: jalankan ulang `php artisan storage:link`.
- **403 setelah login**: pastikan memakai akun dengan role yang sesuai untuk menu tersebut.
