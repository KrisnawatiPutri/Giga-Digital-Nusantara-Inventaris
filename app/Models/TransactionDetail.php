<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = []; // Agar semua kolom bisa diisi

    // Relasi ke Barang (Item)
    // "Setiap detail transaksi pasti milik 1 jenis barang"
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke Transaksi Utama (Transaction)
    // "Setiap detail pasti menempel pada 1 nota transaksi utama"
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    // Relasi ke Detail Barang SN (ItemDetail) - Opsional, buat jaga-jaga
    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class);
    }
}