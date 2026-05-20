<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<div class="overlay" id="overlay"></div>
<?= $this->include('component/pembayaran/pembayaran-form') ?>
<section class="wrapper body">
    <div class="table-header">
        <div class="filter-wrapper">
            <select class="filter-data">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="pembayaran/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>ID Pelanggan</th>
                    <th>Nama</th>
                    <th>Paket</th>
                    <th>Periode</th>
                    <th>Total Tagihan</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal Bayar</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-body">
            </tbody>
        </table>
        <div class="table-footer">
            <div class="showing-data"></div>
            <div class="custom-pagination"></div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>