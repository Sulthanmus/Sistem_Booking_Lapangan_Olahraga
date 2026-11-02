<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_booking');
            $table->string('metode_pembayaran');
            $table->decimal('jumlah', 12, 2);
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', [
                'menunggu_verifikasi',
                'diverifikasi',
                'ditolak',
            ])->default('menunggu_verifikasi');
            $table->timestamps();

            $table->foreign('id_booking')
                ->references('id_booking')
                ->on('booking')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
