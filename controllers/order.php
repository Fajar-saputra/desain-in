<?php 
require_once __DIR__ . '/../config/helpers.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan - DesainIn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #3498db; }
        .order-container { display: flex; gap: 30px; padding: 40px; background: #f8f9fa; min-height: 100vh; }
        .form-card, .preview-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-card { flex: 1; }
        .preview-card { flex: 0.8; position: sticky; top: 40px; height: fit-content; text-align: center; }
        
        /* Preview Styling */
        .canvas-area { 
            background: #e0e0e0; border-radius: 15px; padding: 40px; 
            display: flex; align-items: center; justify-content: center; min-height: 300px;
        }
        .design-preview {
            background: white; border: 2px solid var(--primary-blue);
            transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;
            position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .dimensi-label { position: absolute; font-size: 12px; color: var(--primary-blue); }
        .label-panjang { top: -25px; border-bottom: 1px solid var(--primary-blue); width: 100%; }
        .label-lebar { right: -45px; transform: rotate(90deg); border-bottom: 1px solid var(--primary-blue); width: 100px; }
    </style>
</head>
<body>

<div class="container-fluid order-container">
    <!-- Form Kiri -->
    <div class="form-card">
        <h2 class="mb-4 fw-bold">Buat pesananmu Sekarang</h2>
        <form action="process_order.php" method="POST" enctype="multipart/form-data">
            
            <select class="form-select mb-3" name="jenis_desain">
                <option selected disabled>Jenis Desain</option>
                <option value="banner">Banner / Spanduk</option>
                <option value="poster">Poster</option>
                <option value="kartu_nama">Kartu Nama</option>
            </select>

            <div class="row g-2 mb-3">
                <div class="col-4"><input type="number" id="inputPanjang" class="form-control" placeholder="Panjang" name="panjang"></div>
                <div class="col-4"><input type="number" id="inputLebar" class="form-control" placeholder="Lebar" name="lebar"></div>
                <div class="col-4">
                    <select class="form-select" id="inputSatuan">
                        <option value="cm">cm</option>
                        <option value="m">meter</option>
                        <option value="px">pixel</option>
                    </select>
                </div>
            </div>

            <select class="form-select mb-3" id="selectTipe" name="tipe_output">
                <option value="softcopy">SoftCopy (File Saja)</option>
                <option value="hardcopy">HardCopy (Cetak & Kirim)</option>
            </select>

            <!-- Input Khusus Hardcopy (Hidden by default) -->
            <div id="hardcopyFields" style="display: none;">
                <select class="form-select mb-3" name="jenis_bahan">
                    <option selected disabled>Jenis Bahan Cetak</option>
                    <option>Flexi 280gr</option>
                    <option>Flexi Korea</option>
                    <option>Art Paper</option>
                </select>
                <textarea class="form-control mb-3" name="alamat" placeholder="Alamat Penerima Lengkap"></textarea>
            </div>

            <select class="form-select mb-3" name="metode_pembayaran">
                <option selected disabled>Metode Pembayaran</option>
                <option>Transfer Bank</option>
                <option>E-Wallet (Dana/OVO)</option>
            </select>

            <div class="mb-3">
                <label class="form-label">Upload Referensi</label>
                <input type="file" class="form-control" name="referensi">
            </div>

            <textarea class="form-control mb-4" rows="4" placeholder="Catatan tambahan untuk desainer..."></textarea>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">Pesan Sekarang</button>
        </form>
    </div>

    <!-- Preview Kanan -->
    <div class="preview-card">
        <div class="canvas-area">
            <div id="previewBox" class="design-preview" style="width: 200px; height: 120px;">
                <span class="dimensi-label label-panjang text-center" id="lblPanjang">Panjang</span>
                <span class="dimensi-label label-lebar text-center" id="lblLebar">Lebar</span>
                <p class="m-0 text-muted" style="font-size: 10px;">PREVIEW DESIGN</p>
            </div>
        </div>
        <div class="mt-4 text-start">
            <h4 class="fw-bold">Keterangan</h4>
            <p id="ketTipe" class="badge bg-info">Mode: Softcopy</p>
            <p class="text-muted small">Ukuran preview disesuaikan secara proporsional dengan input panjang dan lebar Anda.</p>
        </div>
    </div>
</div>

<script>
    const inputPanjang = document.getElementById('inputPanjang');
    const inputLebar = document.getElementById('inputLebar');
    const previewBox = document.getElementById('previewBox');
    const lblPanjang = document.getElementById('lblPanjang');
    const lblLebar = document.getElementById('lblLebar');
    const selectTipe = document.getElementById('selectTipe');
    const hardcopyFields = document.getElementById('hardcopyFields');
    const ketTipe = document.getElementById('ketTipe');

    function updatePreview() {
        let p = parseFloat(inputPanjang.value) || 200;
        let l = parseFloat(inputLebar.value) || 120;
        const satuan = document.getElementById('inputSatuan').value;

        // Batasi ukuran visual biar gak jebol layar (maksimal 300px)
        let ratio = p / l;
        let visualWidth, visualHeight;

        if (p >= l) {
            visualWidth = Math.min(p, 300);
            visualHeight = visualWidth / ratio;
        } else {
            visualHeight = Math.min(l, 300);
            visualWidth = visualHeight * ratio;
        }

        previewBox.style.width = visualWidth + 'px';
        previewBox.style.height = visualHeight + 'px';
        
        lblPanjang.innerText = p + ' ' + satuan;
        lblLebar.innerText = l + ' ' + satuan;
    }

    // Toggle Hardcopy Fields
    selectTipe.addEventListener('change', function() {
        if (this.value === 'hardcopy') {
            hardcopyFields.style.display = 'block';
            ketTipe.innerText = "Mode: Hardcopy (Cetak)";
            ketTipe.className = "badge bg-success";
        } else {
            hardcopyFields.style.display = 'none';
            ketTipe.innerText = "Mode: Softcopy";
            ketTipe.className = "badge bg-info";
        }
    });

    inputPanjang.addEventListener('input', updatePreview);
    inputLebar.addEventListener('input', updatePreview);
    document.getElementById('inputSatuan').addEventListener('change', updatePreview);
</script>

</body>
</html>