<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\BarangKeluarExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    // ========================================================================
    // BAGIAN 1: BARANG MASUK (RESTOCK)
    // ========================================================================

    // 1. TAMPILKAN FORM BARANG MASUK
    public function createMasuk()
    {
        // Ambil semua barang untuk dipilih di form
        $items = Item::all();
        return view('transactions.masuk', compact('items'));
    }

    // 2. PROSES SIMPAN BARANG MASUK
    public function storeMasuk(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'item_id' => 'required', // Harus pilih barang
            'tanggal' => 'required|date',
        ]);

        $item = Item::find($request->item_id);

        // -- MULAI TRANSAKSI DATABASE (Agar kalau error, data tidak masuk setengah-setengah) --
        DB::transaction(function () use ($request, $item) {
            
            // A. Buat Header Transaksi (Catatan Umum)
            $transaksi = Transaction::create([
                'nama_teknisi' => 'Admin Gudang', // Bisa diganti user login nanti
                'jenis_transaksi' => 'masuk',
                'tanggal_transaksi' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            // B. Cek Tipe Barang
            if ($item->jenis_input == 'serial') {
                // --- LOGIKA UNTUK MODEM/ROUTER (SN) ---
                
                // Ambil input SN (misal dipisah koma atau enter)
                // Kita akan proses string input menjadi array
                $sn_list = preg_split("/[\r\n,]+/", $request->serial_numbers);
                
                $count = 0;
                foreach ($sn_list as $sn) {
                    $sn = trim($sn); // Hapus spasi
                    if (!empty($sn)) {
                        // 1. Masukkan ke tabel item_details (Data Unik)
                        ItemDetail::create([
                            'item_id' => $item->id,
                            'serial_number' => $sn,
                            'status' => 'ready'
                        ]);

                        // 2. Catat detail transaksi
                        TransactionDetail::create([
                            'transaction_id' => $transaksi->id,
                            'item_id' => $item->id,
                            'serial_number_keluar' => $sn, // Kita pinjam kolom ini utk catat SN masuk
                            'qty' => 1
                        ]);
                        $count++;
                    }
                }
                
                // Update Stok Total di tabel Item
                $item->increment('stok', $count);

            } else {
                // --- LOGIKA UNTUK KABEL/KONEKTOR (Non-Serial) ---
                
                $qty = $request->qty;

                // 1. Update Stok Langsung
                $item->increment('stok', $qty);

                // 2. Catat detail transaksi
                TransactionDetail::create([
                    'transaction_id' => $transaksi->id,
                    'item_id' => $item->id,
                    'qty' => $qty
                ]);
            }
        });

        return redirect()->route('items.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    // ========================================================================
    // BAGIAN 2: BARANG KELUAR (DISTRIBUSI / KASIR)
    // ========================================================================

    // 3. TAMPILKAN HALAMAN KASIR (BARANG KELUAR)
    public function createKeluar()
    {
        // Kita butuh data Barang dan Teknisi
        $items = Item::all();
        // Asumsi kamu punya model Technician, kalau belum pakai string manual dulu gak apa2
        // $technicians = \App\Models\Technician::all(); 
        
        return view('transactions.keluar', compact('items'));
    }

    // 4. PROSES CHECKOUT (SIMPAN TRANSAKSI)
    public function storeKeluar(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_teknisi' => 'required',
            'cart_data' => 'required', // Ini data keranjang dalam bentuk JSON String
        ]);

        // Decode JSON dari Frontend menjadi Array PHP
        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        DB::transaction(function () use ($request, $cart) {
            
            // A. Buat Header Transaksi
            $transaksi = Transaction::create([
                'nama_teknisi' => $request->nama_teknisi,
                'nomor_tiket' => $request->nomor_tiket, // Opsional
                'jenis_transaksi' => 'keluar',
                'tanggal_transaksi' => now(),
            ]);

            // B. Loop setiap barang di keranjang
            foreach ($cart as $itemCart) {
                
                $itemDB = Item::find($itemCart['id']);

                if ($itemCart['type'] == 'serial') {
                    // --- LOGIKA BARANG SN (MODEM) ---
                    $sn = $itemCart['sn'];

                    // 1. Cari SN spesifik di database
                    $detail = ItemDetail::where('serial_number', $sn)
                                        ->where('item_id', $itemCart['id'])
                                        ->first();

                    // Cek validasi stok (PENTING!)
                    if (!$detail || $detail->status != 'ready') {
                        throw new \Exception("Gagal! SN $sn tidak ditemukan atau statusnya tidak Ready.");
                    }

                    // 2. Update Status SN jadi 'terpasang'
                    $detail->update(['status' => 'terpasang']);

                    // 3. Catat Detail Transaksi
                    TransactionDetail::create([
                        'transaction_id' => $transaksi->id,
                        'item_id' => $itemDB->id,
                        'item_detail_id' => $detail->id, // Relasi ke SN spesifik
                        'serial_number_keluar' => $sn,
                        'qty' => 1
                    ]);

                } else {
                    // --- LOGIKA BARANG NON-SERIAL (KABEL) ---
                    
                    // 1. Catat Detail Transaksi
                    TransactionDetail::create([
                        'transaction_id' => $transaksi->id,
                        'item_id' => $itemDB->id,
                        'qty' => $itemCart['qty']
                    ]);
                }

                // C. Kurangi Stok Total di Master Barang
                $itemDB->decrement('stok', $itemCart['qty']);
            }
        });

        return redirect()->route('items.index')->with('success', 'Barang berhasil dikeluarkan. Stok terupdate!');
    }

    // ========================================================================
    // BAGIAN 3: EXPORT DATA
    // ========================================================================

    // 5. DOWNLOAD EXCEL
    public function exportExcel()
    {
        // Download file bernama 'laporan-barang-keluar.xlsx'
        return Excel::download(new BarangKeluarExport, 'laporan-barang-keluar.xlsx');
    }
}