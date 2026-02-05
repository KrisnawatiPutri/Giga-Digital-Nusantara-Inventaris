@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary" style="color: #0d47a1 !important;">Data Master Barang</h2>
                <p class="text-muted">Kelola stok modem, kabel, dan alat teknisi.</p>
            </div>
            
            <div>
                <a href="{{ route('transactions.masuk') }}" class="btn btn-success me-2 text-white" style="border-radius: 50px;">
                    <i class="fa-solid fa-truck-ramp-box me-2"></i>Restock Barang
                </a>

                <a href="{{ route('transactions.keluar') }}" class="btn btn-primary me-2 text-white" style="border-radius: 50px;">
                    <i class="fa-solid fa-basket-shopping me-2"></i>Barang Keluar (Teknisi)
                </a>

                <a href="{{ route('items.create') }}" class="btn btn-giga-orange">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Barang
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #0d47a1; color: white;">
                            <tr>
                                <th class="py-3 ps-4">Nama Barang</th>
                                <th class="py-3">Kategori</th>
                                <th class="py-3">Jenis</th>
                                <th class="py-3">Total Stok</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr style="cursor: pointer;">
                                <td class="ps-4 fw-bold text-dark">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            @if($item->kategori == 'Perangkat Aktif')
                                                <i class="fa-solid fa-router text-primary"></i>
                                            @elseif($item->kategori == 'Tools')
                                                <i class="fa-solid fa-screwdriver-wrench text-warning"></i>
                                            @else
                                                <i class="fa-solid fa-box-open text-success"></i>
                                            @endif
                                        </div>
                                        {{ $item->nama_barang }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border border-secondary">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->jenis_input == 'serial')
                                        <span class="badge rounded-pill bg-info text-dark">
                                            <i class="fa-solid fa-barcode me-1"></i> Scan SN
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">
                                            <i class="fa-solid fa-hashtag me-1"></i> Jumlah (Qty)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <h5 class="mb-0 fw-bold {{ $item->stok < 5 ? 'text-danger' : 'text-success' }}">
                                        {{ $item->stok }} <small class="text-muted fs-6 fw-normal">{{ $item->satuan }}</small>
                                    </h5>
                                </td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Yakin hapus data ini?');" action="{{ route('items.destroy', $item->id) }}" method="POST">
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="mb-3 opacity-50">
                                    <br>
                                    Belum ada data barang. Yuk tambah sekarang!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection