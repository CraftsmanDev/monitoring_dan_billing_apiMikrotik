<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<div class="overlay" id="overlay"></div>
<?= $this->include('component/perbaikan/add-form') ?>
<?= $this->include('component/perbaikan/edit-form') ?>
<section class="wrapper body">
    <div class="table-header">
        <div class="filter-wrapper">
            <select class="filter-data">
                <option value="3">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class="action">
            <button action='tambah' style="--action-color: #0e75fb;"><i class="fa-solid fa-box"></i> Tambah Perbaikan</button>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="perbaikan/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id Pelanggan</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Anggaran</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>action</th>
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