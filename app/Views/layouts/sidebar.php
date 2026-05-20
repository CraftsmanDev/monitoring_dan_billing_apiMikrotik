<?php $role = session()->get('role'); ?>
<aside class="sidebar" id="sidebar">
    <div class="form-header mt-sm">
        <img src="<?= base_url('assest/logo.png') ?>" alt="" class="logo">
        <h1 class="title fs-sm">AB NETWORK</h1>
    </div>
    <div class="search">
        <input type="text" class="w-full search-data input-dark bg-dark" placeholder="search..">
        <span class="icon-search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </span>
    </div>
    <span class="navigation">MAIN NAVIGATION</span>
    <?php if ($role == 'admin') { ?>
        <li class="sidebar-menu">
            <a href="<?= base_url('dashboard') ?>" class="menu-item bd-l-p"><i class="fa-solid fa-gauge-high"></i></i><span>Dashboard</span></a>
            <a href="<?= base_url('dashboard/paket_layanan') ?>" class="menu-item bd-l-t"><i class="fas fa-wifi"></i><span>Paket Layanan</span></a>
            <a href="<?= base_url('dashboard/mikrotik') ?>" class="menu-item bd-l-t"><i class="fas fa-server"></i><span>Integrasi Mikrotik</span></a>
            <a href="<?= base_url('dashboard/pelanggan') ?>" class="menu-item bd-l-t"><i class="fa-solid fa-users"></i><span>Kelola Pelanggan</span></a>
            <a href="<?= base_url('dashboard/pembayaran') ?>" class="menu-item bd-l-t"><i class="fas fa-file-invoice-dollar"></i><span>Manajemen Billing</span></a>
            <a href="<?= base_url('dashboard/petugas') ?>" class="menu-item bd-l-t"><i class="fa-solid fa-user-gear"></i><span>Kelola Petugas</span></a>
            <div class="dropdown">
                <span class="menu-item bd-l-t" data-dropdown="menu-sidebar">
                    <i class="fas fa-chart-line"></i>
                    <span class="text">Laporan</span>
                    <i data-menu="menu-sidebar" class="fa-solid fa-angle-left arrow"></i>
                </span>

                <ul class="submenu" data-menu="menu-sidebar">
                    <span class="submenu-title">Laporan</span>
                    <li class=""><a href="<?= base_url('dashboard/laporan-perbaikan') ?>">Laporan Perbaikan</a></li>
                    <li class=""><a href="<?= base_url('dashboard/laporan-keuangan') ?>">Laporan Keuangan</a></li>
                </ul>
            </div>
        </li>
    <?php } elseif ($role == 'billing') { ?>
        <li class="sidebar-menu">
            <a href="<?= base_url('dashboard') ?>" class="menu-item bd-l-p"><i class="fa-solid fa-gauge-high"></i></i><span>Dashboard</span></a>
            <a href="<?= base_url('dashboard/pelanggan') ?>" class="menu-item bd-l-t"><i class="fa-solid fa-users"></i><span>Kelola Pelanggan</span></a>
            <a href="<?= base_url('dashboard/pembayaran') ?>" class="menu-item bd-l-t"><i class="fas fa-file-invoice-dollar"></i><span>Manajemen Billing</span></a>
        </li>
    <?php } elseif ($role == 'services') { ?>
        <li class="sidebar-menu">
            <a href="<?= base_url('dashboard') ?>" class="menu-item bd-l-p"><i class="fa-solid fa-gauge-high"></i></i><span>Dashboard</span></a>
            <a href="<?= base_url('dashboard/pelanggan') ?>" class="menu-item bd-l-t"><i class="fa-solid fa-users"></i><span>Kelola Pelanggan</span></a>
            <a href="<?= base_url('dashboard/perbaikan') ?>" class="menu-item bd-l-t"><i class="fa-solid fa-tools"></i><span>Kelola Perbaikan</span></a>
        </li>
    <?php } ?>
</aside>