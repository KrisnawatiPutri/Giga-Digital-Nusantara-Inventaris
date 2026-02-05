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
    Schema::create('item_details', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel items
        $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); 
        $table->string('serial_number')->unique(); // SN harus unik, gaboleh kembar
        $table->enum('status', ['ready', 'terpasang', 'rusak', 'hilang'])->default('ready');
        $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_details');
    }
};
