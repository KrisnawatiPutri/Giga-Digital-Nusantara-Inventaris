<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('nama_teknisi'); // Siapa yang ambil
        $table->string('nomor_tiket')->nullable(); // Untuk project apa? (Boleh kosong kalau maintenance rutin)
        $table->enum('jenis_transaksi', ['keluar', 'masuk', 'kembali']); // Status alur barang
        $table->date('tanggal_transaksi');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
