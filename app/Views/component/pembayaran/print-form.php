<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>print</title>
</head>
    <link rel="icon" href="<?= base_url('assest/logo.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<style>
@media print {
    body * {
        visibility: hidden;
    }

    #print-area, 
    #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }

    .rincian-pembayaran {
        margin: 20px !important;
        padding: 10px !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
        height: 100%;
    }
    .btn-form-1 {
        display: none !important;
    }
    @page {
        margin: 0;
    }
}
.rincian-pembayaran {
    max-width: 700px;
    margin: 30px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    font-family: 'Segoe UI', sans-serif;
}

.header-pembayaran {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    line-height: 10px;
}

.header-pembayaran img {
    width: 60px;
}

.header-pembayaran h1 {
    font-size: 22px;
    color: #2F5801;
}

.company-info {
    font-size: 12px;
    color: #666;
}

.company-info p {
    margin: 2px 0;
}

.rincian-pembayaran hr {
    border: none;
    border-top: 1px solid #eee;
    margin: 15px 0;
}

.detail-pembayaran {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.pelanggan-info h2,
.tagihan-info h2 {
    font-size: 16px;
    margin-bottom: 8px;
    color: #2F5801;
}

.pelanggan-info p,
.tagihan-info p {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin: 5px 0;
    color: #333;
}

.pelanggan-info strong,
.tagihan-info strong {
    color: #555;
}

.status-lunas {
    color: #2ecc71;
    font-weight: bold;
}

.btn-form-1 {
    margin: 10px;
    display: flex;
    justify-content: end;
    gap: 10px;
}

.btn-form-1 button {
    padding: 10px;
    border: none;
    border-radius: 6px;
    background-color: var(--action-color);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    color: #555;
    transition: 0.3s;
}
</style>
<body>
<div id="print-area">
    <div class="rincian-pembayaran">
        <div class="header-pembayaran">
            <img src="<?= base_url('assest/logo.png') ?>" alt="Logo">
            <h1>Tagihan Internet</h1>
            <span class="company-info">
                <p>Jl. singo mulyo Alamat kedungdowo, Kec. Arjasa, Kbp. situbondo</p>
                <p>Telp: (021) 12345678 | Email: info@contoh.com</p>
            </span>
        </div>
        <hr>
        <div class="detail-pembayaran">
            <div class="pelanggan-info">
                <h2>Informasi Pelanggan</h2>
                <p><strong>ID Pelanggan:</strong><?= $pembayaran['id_pelanggan'] ?></p>
                <p><strong>Nama:</strong> <?= $pembayaran['nama'] ?></p>
                <p><strong>No HP:</strong> <?= $pembayaran['nomor_wa'] ?></p>
            </div>
            <hr>
            <div class="tagihan-info">
                <h2>Rincian Tagihan</h2>
                <p><strong>Paket:</strong> <?= $pembayaran['nama_paket'] ?></p>
                <p><strong>Periode:</strong> <?= $pembayaran['periode'] ?></p>
                <p><strong>Status:</strong> <span class="status-lunas"><?= $pembayaran['status_pembayaran'] ?></span></p>
                <p><strong>Tagihan:</strong> <?= $pembayaran['total_tagihan'] ?></p>
                <p><strong>Total Bayar:</strong> <?= $pembayaran['total_bayar'] ?></p>
                <p><strong>Kembalian:</strong> <?= $pembayaran['kembalian'] ?></p>
                <p><strong>Tanggal Bayar:</strong> <?= $pembayaran['tanggal_bayar'] ?></p>
            </div>
        </div>
    </div>
    <div class="btn-form-1">
        <button class="print" onclick="print()" style="--action-color: #4da6ff; --action-color-hover: #3197fd;">cetak pembayaran</button>
        <button onclick="goHome()" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
    </div>
</div>
</body>
<script>
    function printArea() {
        var printContents = document.getElementById('print-area').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
    function goHome() {
        window.location.href = "<?= base_url('dashboard/pembayaran') ?>";
    }
</script>
</html>