@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        --orange-gradient: linear-gradient(135deg, #f72585 0%, #ff6f00 100%); 
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: 1px solid rgba(255, 255, 255, 0.6);
        --shadow-soft: 0 10px 40px -10px rgba(0,0,0,0.08);
        --radius-xl: 24px;
        --radius-l: 16px;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f0f2f5;
        /* Background Mesh halus di belakang body */
        background-image: 
            radial-gradient(at 0% 0%, hsla(253,16%,7%,0) 0, hsla(253,16%,7%,0) 50%), 
            radial-gradient(at 50% 0%, hsla(225,39%,30%,0) 0, hsla(225,39%,30%,0) 50%), 
            radial-gradient(at 100% 0%, hsla(339,49%,30%,0) 0, hsla(339,49%,30%,0) 50%);
    }

    /* Utilitas Kartu Kaca (Glassmorphism) */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: var(--glass-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-soft);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 20px 40px -10px rgba(67, 97, 238, 0.15);
    }

    /* Header Welcome */
    .welcome-banner {
        background: var(--primary-gradient);
        border-radius: var(--radius-xl);
        color: white;
        padding: 40px;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
    }

    /* Efek lingkaran dekorasi di banner */
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%; left: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -20%; right: 5%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
    }

    /* Stat Cards yang Pop */
    .stat-icon-box {
        width: 60px; height: 60px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }
    
    .bg-soft-blue { background: #e0e7ff; color: #4361ee; }
    .bg-soft-orange { background: #ffe4e6; color: #f72585; }
    .bg-soft-red { background: #fee2e2; color: #ef4444; }

    /* Alert Stok Menipis */
    .alert-item {
        transition: 0.2s;
        border-radius: 12px;
        margin-bottom: 8px;
        border: 1px solid #fee2e2;
    }
    .alert-item:hover { background-color: #fff1f2; }

    /* Table Styling Modern */
    .table-modern thead th {
        background-color: transparent;
        border-bottom: 2px solid #eef0f5;
        color: #8898aa;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding-bottom: 15px;
    }
    
    .table-modern tbody tr {
        transition: 0.2s;
    }
    
    .table-modern tbody tr:hover {
        background-color: #f8f9fe;
        transform: scale(1.005);
    }

    .table-modern td {
        padding: 20px 15px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
    }

    /* Tombol Rounded Pill */
    .btn-pill {
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: 0.3s;
        border: none;
    }

    .btn-gradient-blue {
        background: linear-gradient(90deg, #4361ee, #4cc9f0);
        color: white;
    }
    .btn-gradient-blue:hover { box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4); color: white; transform: translateY(-2px);}

    .btn-gradient-orange {
        background: linear-gradient(90deg, #f72585, #ff6f00);
        color: white;
    }
    .btn-gradient-orange:hover { box-shadow: 0 8px 25px rgba(247, 37, 133, 0.4); color: white; transform: translateY(-2px);}

    /* Floating Icons untuk List Leaderboard */
    .leaderboard-item {
        border-bottom: 1px solid #f0f2f5;
        transition: 0.2s;
    }
    .leaderboard-item:last-child { border-bottom: none; }
    .leaderboard-item:hover { background: #f8faff; padding-left: 20px; }
</style>

<div class="welcome-banner shadow mb-5">
    <div class="row align-items-center position-relative" style="z-index: 2;">
        <div class="col-md-8">
            <h1 class="fw-bolder mb-2">🚀 Dashboard Monitoring</h1>
            <p class="mb-0 opacity-75 fs-5">Pantau performa gudang & aktivitas teknisi Giga Digital secara real-time.</p>
        </div>
        <div class="col-md-4 text-end d-none d-md-block">
            <i class="fa-solid fa-rocket fa-4x opacity-50"></i>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    
    <div class="col-md-3">
        <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted fw-bold mb-1 text-uppercase small">Total Aset</p>
                    <h2 class="fw-bolder display-5 mb-0 text-dark">{{ $totalJenisBarang }}</h2>
                </div>
                <div class="stat-icon-box bg-soft-blue">
                    <i class="fa-solid fa-box"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-soft-blue rounded-pill px-3">📦 Master Data</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted fw-bold mb-1 text-uppercase small">Transaksi</p>
                    <h2 class="fw-bolder display-5 mb-0 text-dark">{{ $totalTransaksi }}</h2>
                </div>
                <div class="stat-icon-box bg-soft-orange">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-soft-orange rounded-pill px-3">⚡ Aktivitas</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="glass-card h-100 p-4 position-relative overflow-hidden">
            <div style="position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; background: #fee2e2; border-radius: 50%; filter: blur(30px);"></div>

            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon-box bg-soft-red me-3" style="width: 45px; height: 45px; font-size: 1.2rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h5 class="fw-bold mb-0 text-danger">Low Stock Alert (< 10)</h5>
            </div>

            <div style="max-height: 120px; overflow-y: auto; padding-right: 5px;">
                @if($stokMenipis->count() > 0)
                    @foreach($stokMenipis as $item)
                    <div class="alert-item d-flex justify-content-between align-items-center p-2 rounded">
                        <span class="fw-semibold text-dark">{{ $item->nama_barang }}</span>
                        <span class="badge bg-danger rounded-pill px-3">Sisa: {{ $item->stok }}</span>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fa-solid fa-circle-check text-success fa-2x mb-2"></i>
                        <p class="text-muted fw-bold">Semua stok aman terkendali!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Tren Barang Keluar</h5>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Bulan Ini</span>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="topItemsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-0 h-100 overflow-hidden">
            <div class="p-4" style="background: linear-gradient(to right, #f72585, #b5179e); color: white;">
                <h5 class="fw-bold mb-0"><i class="fa-solid fa-crown me-2 text-warning"></i>Top Teknisi</h5>
                <small class="opacity-75">Pejuang Giga paling rajin minggu ini</small>
            </div>
            <div class="p-3">
                @forelse($topTeknisi as $index => $tech)
                <div class="leaderboard-item d-flex align-items-center p-3 rounded-3 mb-2">
                    <div class="me-3 d-flex align-items-center justify-content-center fw-bold text-white rounded-circle shadow-sm" 
                         style="width: 40px; height: 40px; background: {{ $index == 0 ? '#ffb703' : ($index == 1 ? '#adb5bd' : '#cd7f32') }}">
                        #{{ $index + 1 }}
                    </div>
                    
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold text-dark">{{ $tech->nama_teknisi }}</h6>
                        <small class="text-muted">{{ $tech->total_job }}x Pengambilan</small>
                    </div>
                    
                    @if($index == 0)
                        <i class="fa-solid fa-trophy text-warning fa-lg"></i>
                    @endif
                </div>
                @empty
                    <div class="text-center py-5 text-muted">Belum ada data.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="glass-card p-4">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold mb-1">🗂️ Data Master Barang</h4>
                    <p class="text-muted small mb-0">Manajemen stok inventaris Giga Digital</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('transactions.export') }}" class="btn btn-pill btn-white border text-success bg-white">
                        <i class="fa-solid fa-file-excel"></i> Export
                    </a>
                    <a href="{{ route('transactions.masuk') }}" class="btn btn-pill btn-white border text-primary bg-white">
                        <i class="fa-solid fa-truck-ramp-box"></i> Restock
                    </a>
                    <a href="{{ route('transactions.keluar') }}" class="btn btn-pill btn-gradient-blue border-0">
                        <i class="fa-solid fa-basket-shopping"></i> Keluar
                    </a>
                    <a href="{{ route('items.create') }}" class="btn btn-pill btn-gradient-orange border-0">
                        <i class="fa-solid fa-plus"></i> Baru
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Item Name</th>
                            <th>Kategori</th>
                            <th>Tipe Input</th>
                            <th>Stok Tersedia</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" 
                                         style="width: 45px; height: 45px; background: #f8f9fa; color: #4361ee;">
                                        @if($item->kategori == 'Perangkat Aktif') <i class="fa-solid fa-router"></i>
                                        @elseif($item->kategori == 'Tools') <i class="fa-solid fa-screwdriver-wrench"></i>
                                        @else <i class="fa-solid fa-box-open"></i> @endif
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $item->nama_barang }}</span>
                                        <span class="small text-muted">ID: #{{ $item->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border rounded-pill px-3 py-2">{{ $item->kategori }}</span></td>
                            <td>
                                @if($item->jenis_input == 'serial')
                                    <span class="text-primary fw-bold"><i class="fa-solid fa-barcode me-1"></i> SN Scan</span>
                                @else
                                    <span class="text-secondary fw-bold"><i class="fa-solid fa-hashtag me-1"></i> Qty</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <h5 class="fw-bold mb-0 me-2 {{ $item->stok < 10 ? 'text-danger' : 'text-success' }}">{{ $item->stok }}</h5>
                                    <span class="text-muted small">{{ $item->satuan }}</span>
                                </div>
                                <div class="progress" style="height: 4px; width: 60px;">
                                    <div class="progress-bar {{ $item->stok < 10 ? 'bg-danger' : 'bg-success' }}" 
                                         role="progressbar" style="width: {{ min($item->stok, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <form onsubmit="return confirm('Hapus barang ini?');" action="{{ route('items.destroy', $item->id) }}" method="POST">
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm me-1" style="width: 35px; height: 35px;"><i class="fa-solid fa-pen"></i></a>
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" style="width: 35px; height: 35px;"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Kosong melompong... 🍃</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartLabels) !!};
    const dataValues = {!! json_encode($chartValues) !!};

    const ctx = document.getElementById('topItemsChart').getContext('2d');
    
    // Bikin Gradient Warna Biru-Ungu biar Aesthetic
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(67, 97, 238, 0.8)'); // Biru
    gradient.addColorStop(1, 'rgba(58, 12, 163, 0.2)'); // Ungu Pudar

    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Unit Keluar',
                data: dataValues,
                backgroundColor: gradient,
                borderRadius: 8, // Bar jadi bulat atasnya
                barThickness: 30, // Bar lebih gemuk
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // Hilangkan legend biar bersih
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f2f5', borderDash: [5, 5] },
                    ticks: { stepSize: 1 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>

@endsection