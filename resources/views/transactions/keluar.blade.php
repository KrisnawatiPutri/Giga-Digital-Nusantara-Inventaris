@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-barcode me-2"></i>Input Barang</h5>
            </div>
            <div class="card-body">
                
                <div id="reader" width="100%" class="mb-3 border rounded"></div>
                <div class="d-grid mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="startScanner()">
                        <i class="fa-solid fa-camera"></i> Buka Kamera
                    </button>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="fw-bold">Pilih Jenis Barang</label>
                    <select id="select_barang" class="form-select">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" 
                                    data-name="{{ $item->nama_barang }}" 
                                    data-type="{{ $item->jenis_input }}"
                                    data-stok="{{ $item->stok }}">
                                {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="input_sn_wrapper" class="mb-3 d-none">
                    <label class="fw-bold">Serial Number (Hasil Scan)</label>
                    <div class="input-group">
                        <input type="text" id="input_sn" class="form-control" placeholder="Scan atau ketik SN...">
                        <button class="btn btn-primary" type="button" onclick="addItemToCart()">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div id="input_qty_wrapper" class="mb-3 d-none">
                    <label class="fw-bold">Jumlah Ambil (Meter/Pcs)</label>
                    <div class="input-group">
                        <input type="number" id="input_qty" class="form-control" placeholder="0">
                        <button class="btn btn-primary" type="button" onclick="addItemToCart()">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-7">
        <form action="{{ route('transactions.storeKeluar') }}" method="POST" id="formCheckout">
            @csrf
            
            <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="small text-muted">Nama Teknisi</label>
                            <input type="text" name="nama_teknisi" class="form-control" required placeholder="Siapa yang ambil?">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small text-muted">No. Tiket / Pelanggan (Opsional)</label>
                            <input type="text" name="nomor_tiket" class="form-control" placeholder="Contoh: TIKET-001 / Pak Budi">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">Keranjang Alat</h5>
                    <span class="badge bg-secondary" id="total_items">0 Item</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Barang</th>
                                <th>Detail (SN/Qty)</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cart_table_body">
                            <tr id="empty_row">
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-cart-shopping mb-2 fs-4"></i><br>
                                    Belum ada barang diambil.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white p-3">
                    <input type="hidden" name="cart_data" id="cart_data_input">
                    
                    <button type="submit" class="btn btn-giga-orange w-100 fw-bold py-2">
                        <i class="fa-solid fa-paper-plane me-2"></i> PROSES BARANG KELUAR
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let cart = []; // Array untuk menampung barang sementara

    // 1. LOGIKA SCANNER
    function onScanSuccess(decodedText, decodedResult) {
        // Mainkan suara beep
        let audio = new Audio('https://www.soundjay.com/button/beep-07.mp3');
        audio.play();

        // Masukkan hasil scan ke kolom input SN
        document.getElementById('input_sn').value = decodedText;
        
        // Matikan scanner biar gak scan berkali-kali
        html5QrcodeScanner.clear();

        // Otomatis klik tombol tambah (Opsional, biar cepat)
        addItemToCart(); 
        alert("SN Terdeteksi: " + decodedText);
    }

    let html5QrcodeScanner;
    function startScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    }

    // 2. LOGIKA PILIH BARANG (Dropdown)
    const selectBarang = document.getElementById('select_barang');
    const wrapperSN = document.getElementById('input_sn_wrapper');
    const wrapperQty = document.getElementById('input_qty_wrapper');

    selectBarang.addEventListener('change', function() {
        let option = this.options[this.selectedIndex];
        let type = option.getAttribute('data-type');

        wrapperSN.classList.add('d-none');
        wrapperQty.classList.add('d-none');

        if(type === 'serial') {
            wrapperSN.classList.remove('d-none');
        } else if (type === 'non-serial') {
            wrapperQty.classList.remove('d-none');
        }
    });

    // 3. LOGIKA TAMBAH KE KERANJANG
    function addItemToCart() {
        let option = selectBarang.options[selectBarang.selectedIndex];
        
        if(selectBarang.value === "") {
            alert("Pilih barang dulu!"); return;
        }

        let id = selectBarang.value;
        let name = option.getAttribute('data-name');
        let type = option.getAttribute('data-type');
        let snInput = document.getElementById('input_sn');
        let qtyInput = document.getElementById('input_qty');
        
        let itemData = {
            id: id,
            name: name,
            type: type,
            sn: null,
            qty: 0
        };

        // Validasi Input
        if (type === 'serial') {
            if(snInput.value.trim() === "") { alert("SN harus diisi/scan!"); return; }
            
            // Cek apakah SN sudah ada di keranjang (biar gak dobel)
            let exists = cart.find(x => x.sn === snInput.value);
            if(exists) { alert("SN ini sudah masuk keranjang!"); return; }

            itemData.sn = snInput.value;
            itemData.qty = 1; // Modem pasti 1 per SN
            snInput.value = ""; // Kosongkan input
        } else {
            if(qtyInput.value <= 0) { alert("Jumlah harus lebih dari 0!"); return; }
            itemData.qty = parseInt(qtyInput.value);
            qtyInput.value = ""; // Kosongkan input
        }

        // Masukkan ke Array Cart
        cart.push(itemData);
        renderTable();
    }

    // 4. RENDER TABEL HTML
    function renderTable() {
        let tbody = document.getElementById('cart_table_body');
        tbody.innerHTML = ""; // Bersihkan tabel

        if(cart.length === 0) {
            tbody.innerHTML = '<tr id="empty_row"><td colspan="3" class="text-center py-4 text-muted">Keranjang Kosong</td></tr>';
        }

        cart.forEach((item, index) => {
            let detail = item.type === 'serial' 
                ? `<span class="badge bg-info text-dark">SN: ${item.sn}</span>` 
                : `<span class="badge bg-secondary">${item.qty} Unit/Meter</span>`;

            let row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${detail}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem(${index})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        // Update Hidden Input (Ini yang dikirim ke Backend!)
        document.getElementById('cart_data_input').value = JSON.stringify(cart);
        document.getElementById('total_items').innerText = cart.length + " Item";
    }

    // 5. HAPUS ITEM
    function hapusItem(index) {
        cart.splice(index, 1);
        renderTable();
    }
</script>
@endsection