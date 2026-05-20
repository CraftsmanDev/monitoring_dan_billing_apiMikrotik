<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<div class="overlay" id="overlay"></div>
<?= $this->include('component/paket_layanan/add-form') ?>
<?= $this->include('component/paket_layanan/edit-form') ?>
<section class="wrapper body">
    <div class="table-header">
        <div class="filter-wrapper">
            <select class="filter-data">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="action">
            <button action='tambah' style="--action-color: #0e75fb;"><i class="fa-solid fa-box"></i> Tambah Paket</button>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="paket_layanan/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Kecepatan</th>
                    <th>Harga Paket</th>
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