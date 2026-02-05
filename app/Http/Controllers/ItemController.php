<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // MENAMPILKAN DATA (READ)
    public function index()
    {
        $items = Item::latest()->get(); // Ambil semua data barang
        return view('items.index', compact('items')); // Kirim ke view index
    }

    // MENAMPILKAN FORM TAMBAH (CREATE VIEW)
    public function create()
    {
        return view('items.create');
    }

    // MENYIMPAN DATA KE DB (CREATE PROCESS)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required',
            'kategori' => 'required',
            'jenis_input' => 'required',
            'satuan' => 'required'
        ]);

        // Simpan
        Item::create($request->all());

        // Kembali ke halaman index dengan pesan
        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // MENAMPILKAN FORM EDIT (UPDATE VIEW)
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // MENGUPDATE DATA KE DB (UPDATE PROCESS)
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required'
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diupdate!');
    }

    // MENGHAPUS DATA (DELETE)
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }
}