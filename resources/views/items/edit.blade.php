@extends('layouts.app')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4>Edit Barang</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf 
            @method('PUT') <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ $item->nama_barang }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="Perangkat Aktif" {{ $item->kategori == 'Perangkat Aktif' ? 'selected' : '' }}>Perangkat Aktif</option>
                        <option value="Material Instalasi" {{ $item->kategori == 'Material Instalasi' ? 'selected' : '' }}>Material Instalasi</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jenis Input</label>
                    <select name="jenis_input" class="form-select">
                        <option value="serial" {{ $item->jenis_input == 'serial' ? 'selected' : '' }}>Serial Number</option>
                        <option value="non-serial" {{ $item->jenis_input == 'non-serial' ? 'selected' : '' }}>Non-Serial</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Stok Saat Ini</label>
                    <input type="number" name="stok" class="form-control" value="{{ $item->stok }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control" value="{{ $item->satuan }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Barang</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection