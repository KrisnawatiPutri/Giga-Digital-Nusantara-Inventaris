<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController; // Import controller Dashboard ditambahkan

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama sekarang menampilkan Dashboard (bukan redirect lagi)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route Resource untuk Items (CRUD Barang Master)
Route::resource('items', ItemController::class);

// --- Route untuk Transaksi (Barang Masuk) ---
Route::get('/barang-masuk', [TransactionController::class, 'createMasuk'])->name('transactions.masuk');
Route::post('/barang-masuk', [TransactionController::class, 'storeMasuk'])->name('transactions.storeMasuk');

// --- Route untuk Transaksi (Barang Keluar) ---
Route::get('/barang-keluar', [TransactionController::class, 'createKeluar'])->name('transactions.keluar');
Route::post('/barang-keluar', [TransactionController::class, 'storeKeluar'])->name('transactions.storeKeluar');

// --- Route Export Data ---
Route::get('/export-barang-keluar', [TransactionController::class, 'exportExcel'])->name('transactions.export');