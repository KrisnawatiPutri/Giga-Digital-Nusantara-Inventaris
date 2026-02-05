<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Wajib import ini untuk query agregate

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KARTU RINGKASAN
        $totalJenisBarang = Item::count();
        $totalTransaksi = Transaction::count();
        
        // 2. LOW STOCK ALERT (Stok kurang dari 10)
        $stokMenipis = Item::where('stok', '<', 10)->orderBy('stok', 'asc')->get();

        // 3. GRAFIK BARANG TERLARIS (Top 5 Bulan Ini)
        $topItems = TransactionDetail::select('item_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('transaction', function($q) {
                // Filter hanya transaksi bulan ini & jenis 'keluar'
                $q->whereMonth('tanggal_transaksi', now()->month)
                  ->whereYear('tanggal_transaksi', now()->year)
                  ->where('jenis_transaksi', 'keluar');
            })
            ->with('item') // Load nama barang
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Siapkan data untuk Chart.js (Pisahkan Label dan Data)
        $chartLabels = $topItems->pluck('item.nama_barang');
        $chartValues = $topItems->pluck('total_qty');

        // 4. TEKNISI PALING RAJIN (Minggu Ini)
        $topTeknisi = Transaction::select('nama_teknisi', DB::raw('COUNT(*) as total_job'))
            ->where('jenis_transaksi', 'keluar')
            ->whereBetween('tanggal_transaksi', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('nama_teknisi')
            ->orderByDesc('total_job')
            ->limit(3)
            ->get();

        // --- [TAMBAHAN BARU] ---
        // Mengambil semua data barang untuk ditampilkan di tabel dashboard
        $items = Item::latest()->get();

        return view('dashboard', compact(
            'totalJenisBarang', 
            'totalTransaksi', 
            'stokMenipis', 
            'chartLabels', 
            'chartValues',
            'topTeknisi',
            'items' // <-- Variabel baru ditambahkan ke view
        ));
    }
}