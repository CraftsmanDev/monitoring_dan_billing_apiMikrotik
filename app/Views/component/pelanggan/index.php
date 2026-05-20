<?php $role = session()->get('role'); ?>
<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<div class="overlay" id="overlay"></div>
<?= $this->include('component/pelanggan/add-form') ?>
<?= $this->include('component/pelanggan/import_excel') ?>
<?= $this->include('component/pelanggan/edit-form') ?>
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
       <?php if ($role == 'admin') { ?>
            <div class="action">
                <button 
                style="--action-color:#08A185;"
                onclick="window.location.href='<?= base_url('dashboard/pelanggan/export_template') ?>'">
                    <i class="fa-solid fa-file-export"></i>
                    <span class="text-full">Export Pelanggan</span>
                    <span class="text-short">Export</span>
                </button>
                <button action="import" style="--action-color:#08A14D;">
                    <i class="fa-solid fa-file-import"></i>
                    <span class="text-full">Import Pelanggan</span>
                    <span class="text-short">Import</span>
                </button>
                <button action='tambah' style="--action-color:#0479e1;">
                    <i class="fa-solid fa-plus"></i>
                    <span class="text-full">Tambah Pelanggan</span>
                    <span class="text-short">Tambah</span>
                </button>
            </div>
        <?php }?>
    </div>
    <div class="info-status">
        <div class="status-card isolir">
            <p class="number"><?= count($isolir) ?></p>
            <span class="label">Isolir</span>
        </div>
        <div class="status-card nunggak">
            <p class="number">0</p>
            <span class="label">Nunggak</span>
        </div>

        <div class="status-card lunas">
            <p class="number"><?= $sudah_bayar ?></p>
            <span class="label">Sudah Bayar</span>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="pelanggan/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pelanggan</th>
                    <th>Nama</th>
                    <th>Nomor Tlp</th>
                    <th>Area</th>
                    <th>Paket</th>
                    <th>Tarif</th>
                    <th>Tanggal register</th>
                    <?php if ($role == 'admin') {?>
                        <th>Action</th>
                    <?php }?>
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