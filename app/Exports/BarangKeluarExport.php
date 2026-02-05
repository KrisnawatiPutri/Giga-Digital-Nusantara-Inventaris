<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BarangKeluarExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    // 1. QUERY DATA YANG MAU DIAMBIL
    public function query()
    {
        // Ambil detail transaksi, tapi HANYA yang jenisnya 'keluar'
        return TransactionDetail::query()
            ->with(['transaction', 'item']) // Load relasi biar hemat query
            ->whereHas('transaction', function($q) {
                $q->where('jenis_transaksi', 'keluar');
            })
            ->latest(); // Urutkan dari yang terbaru
    }

    // 2. JUDUL KOLOM DI EXCEL (Header)
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Teknisi',
            'Tiket / Tujuan',
            'Nama Barang',
            'Kategori',
            'Serial Number (SN)',
            'Jumlah (Qty)',
        ];
    }

    // 3. ISI DATA PER BARIS (Mapping)
    public function map($detail): array
    {
        return [
            $detail->transaction->tanggal_transaksi,      // Kolom A
            $detail->transaction->nama_teknisi,           // Kolom B
            $detail->transaction->nomor_tiket ?? '-',     // Kolom C
            $detail->item->nama_barang,                   // Kolom D
            $detail->item->kategori,                      // Kolom E
            $detail->serial_number_keluar ?? '-',         // Kolom F (SN jika ada)
            $detail->qty . ' ' . $detail->item->satuan,   // Kolom G (misal: 100 meter)
        ];
    }
}