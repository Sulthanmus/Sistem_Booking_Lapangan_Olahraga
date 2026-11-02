<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('kode_booking')->unique();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('id_lapangan');
            $table->unsignedBigInteger('id_jadwal')->unique();
            $table->dateTime('tanggal_booking');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status_booking', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'terkonfirmasi',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            $table->timestamps();

            $table->foreign('id_lapangan')
                ->references('id_lapangan')
                ->on('lapangan')
                ->cascadeOnDelete();

            $table->foreign('id_jadwal')
                ->references('id_jadwal')
                ->on('jadwal')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
