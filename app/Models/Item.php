<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Ini penting agar data bisa disimpan (Mass Assignment)
    // guarded = [] artinya "tidak ada kolom yang dilarang diisi" (semua boleh diisi)
    protected $guarded = [];

    /**
     * Relasi: Satu jenis barang bisa punya banyak unit (SN)
     * Ini menghubungkan Model Item ke Model ItemDetail
     */
    public function details()
    {
        return $this->hasMany(ItemDetail::class);
    }
}