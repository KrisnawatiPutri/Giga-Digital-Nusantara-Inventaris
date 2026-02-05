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
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('nama_barang'); // Contoh: Modem ZTE F609
        $table->string('kategori'); // Contoh: Modem, Kabel, Aksesoris
        $table->enum('jenis_input', ['serial', 'non-serial']); // Penanda: Scan SN atau Input Jumlah?
        $table->integer('stok')->default(0); // Stok Total
        $table->string('satuan')->default('pcs'); // pcs, meter, pack
        $table->string('gambar')->nullable(); // Foto barang (opsional)
        $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
