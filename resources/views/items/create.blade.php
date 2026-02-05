@extends('layouts.app')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Tambah Barang Baru</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required placeholder="Contoh: Modem ZTE F609">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="Perangkat Aktif">Perangkat Aktif (Modem/Router)</option>
                        <option value="Material Instalasi">Material Instalasi (Kabel/Klem)</option>
                        <option value="Tools">Tools (Tang/Splicer)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jenis Input</label>
                    <select name="jenis_input" class="form-select">
                        <option value="serial">Serial Number (Wajib Scan)</option>
                        <option value="non-serial">Non-Serial (Input Jumlah)</option>
                    </select>
                    <small class="text-muted">Pilih 'Serial' untuk Modem, 'Non-Serial' untuk Kabel/Konektor.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" class="form-control" value="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control" placeholder="pcs, meter, pack">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Barang</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection