<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<div class="overlay" id="overlay"></div>
<?= $this->include('component/petugas/add-form') ?>
<?= $this->include('component/petugas/edit-form') ?>
<section class="wrapper body">
    <div class="table-header">
        <div class="filter-wrapper">
            <select class="filter-data">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class="action">
            <button btn-action="add" style="--action-color: #0e75fb;"><i class="fa-solid fa-plus"></i> Tambah Petugas</button>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="petugas/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>username</th>
                    <th>Nama Lengkap</th>
                    <th>Nomor tlp</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-body">
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>