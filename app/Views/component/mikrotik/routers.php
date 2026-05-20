<?php $role = session()->get('role'); ?>
<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<div class="overlay" id="overlay"></div>
<?= $this->include('component/mikrotik/add-form') ?>
<?= $this->include('component/mikrotik/edit-form') ?>
<div>
    <section class="wrapper body">
    <div class="table-header">
        <div class="action">
            <button action='tambah' style="--action-color:#0479e1;">
                <i class="fa-solid fa-plus"></i>
                <span class="text-full">Tambah Mikrotik</span>
                <span class="text-short">Tambah</span>
            </button>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="mikrotik/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Address</th>
                    <th>username</th>
                    <th>Port</th>
                    <th>Status</th>
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
</div>
<?= $this->endSection() ?>