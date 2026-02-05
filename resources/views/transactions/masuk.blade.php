@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white"> <h4 class="mb-0 fw-bold"><i class="fa-solid fa-truck-ramp-box me-2"></i> Input Barang Masuk (Restock)</h4>
            </div>
            <div class="card-body p-4">
                
                <form action="{{ route('transactions.storeMasuk') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">Tanggal Masuk</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">Keterangan / Supplier</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Pembelian dari Vendor A">
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="fw-bold mb-1">Pilih Barang yang Mau Ditambah</label>
                        <select id="pilih_barang" name="item_id" class="form-select form-select-lg" required>
                            <option value="">-- Cari Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-type="{{ $item->jenis_input }}">
                                    {{ $item->nama_barang }} (Stok saat ini: {{ $item->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="area_serial" class="mb-4 p-3 bg-light rounded border border-success d-none">
                        <label class="fw-bold text-success mb-2">Scan Serial Number (SN)</label>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-info-circle"></i> Gunakan Barcode Scanner. Tekan Enter setelah setiap scan.</p>
                        <textarea name="serial_numbers" rows="5" class="form-control font-monospace" placeholder="SN12345&#10;SN67890&#10;..."></textarea>
                        <small class="text-muted">Bisa input banyak sekaligus (satu SN per baris).</small>
                    </div>

                    <div id="area_qty" class="mb-4 p-3 bg-light rounded border border-secondary d-none">
                        <label class="fw-bold mb-2">Masukkan Jumlah Penambahan</label>
                        <div class="input-group">
                            <input type="number" name="qty" class="form-control" placeholder="0">
                            <span class="input-group-text">Unit/Meter</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fa-solid fa-save me-2"></i> SIMPAN STOK MASUK
                    </button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('pilih_barang').addEventListener('change', function() {
        // Ambil elemen yang dipilih
        let selectedOption = this.options[this.selectedIndex];
        let inputType = selectedOption.getAttribute('data-type');
        
        // Ambil area input
        let areaSerial = document.getElementById('area_serial');
        let areaQty = document.getElementById('area_qty');

        // Reset tampilan (sembunyikan semua dulu)
        areaSerial.classList.add('d-none');
        areaQty.classList.add('d-none');

        // Tampilkan sesuai tipe
        if(inputType === 'serial') {
            areaSerial.classList.remove('d-none');
        } else if (inputType === 'non-serial') {
            areaQty.classList.remove('d-none');
        }
    });
</script>
@endsection