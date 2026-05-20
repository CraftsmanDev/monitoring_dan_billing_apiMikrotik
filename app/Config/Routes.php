<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('logout', 'Login::logout');
$routes->get('/', 'Login::index');
//group login
$routes->group('login', ['filter' => 'RedirectAuth'], function ($routes) {
    $routes->post('/', 'Login::store');
    $routes->get('/', 'Login::index');
});

$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('paket_layanan', 'Paket::index');
    $routes->get('mikrotik', 'Mikrotik::index');
    $routes->get('pelanggan', 'Pelanggan::index');
    $routes->get('pembayaran', 'Pembayaran::index');
    $routes->get('perbaikan', 'Perbaikan::index');
    $routes->get('laporan-keuangan', 'Laporan::keuangan');
    $routes->get('laporan-perbaikan', 'Laporan::perbaikan');
    $routes->get('petugas', 'Petugas::index');

    //routes fetch
    $routes->get('fetch', 'Dashboard::fetch');
    $routes->get('traffic', 'Dashboard::traffic');
    $routes->get('count', 'Dashboard::count');
    $routes->get('active', 'Dashboard::active');
    $routes->get('paket_layanan/fetch', 'Paket::fetch');
    $routes->get('mikrotik/fetch', 'Mikrotik::fetch');
    $routes->get('pelanggan/fetch', 'Pelanggan::fetch');
    $routes->get('pembayaran/fetch', 'Pembayaran::fetch');
    $routes->get('pelanggan/get', 'Pembayaran::getPelanggan');
    $routes->get('petugas/fetch', 'Petugas::fetch');
    $routes->get('perbaikan/fetch', 'Perbaikan::fetch');
    $routes->get('laporan/perbaikan/fetch', 'Laporan::perbaikanfetch');
    $routes->get('laporan/keuangan/fetch', 'Laporan::keuanganfetch');
    $routes->get('message', 'Message::getmessage');

    // routes post
    $routes->post('paket_layanan/add', 'Paket::store');
    $routes->post('mikrotik/add', 'Mikrotik::store');
    $routes->post('mikrotik/test', 'Mikrotik::test');
    $routes->post('pelanggan/add', 'Pelanggan::store');
    $routes->post('pelanggan/status', 'Pelanggan::status');
    $routes->post('pembayaran/add', 'Pembayaran::store');
    $routes->post('petugas/add', 'Petugas::store');
    $routes->post('perbaikan/add', 'Perbaikan::store');

    // routes put
    $routes->post('paket_layanan/put', 'Paket::update');
    $routes->post('mikrotik/put', 'Mikrotik::update');
    $routes->post('pelanggan/put', 'Pelanggan::update');
    $routes->post('pembayaran/put', 'Pembayaran::update');
    $routes->post('pembayaran/konfirmasi', 'Pembayaran::konfirmasi');
    $routes->post('petugas/put', 'Petugas::update');
    $routes->post('perbaikan/put', 'Perbaikan::update');
    $routes->post('profil/put', 'Profil::update');
    $routes->post('message/read-all', 'Message::readAll');

    //routes delete
    $routes->post('paket_layanan/delete', 'Paket::delete');
    $routes->post('pelanggan/delete', 'Pelanggan::delete');
    $routes->post('pembayaran/delete', 'Pembayaran::delete');
    $routes->post('petugas/delete', 'Petugas::delete');
    $routes->post('perbaikan/delete', 'Perbaikan::delete');

    //print dan view
    $routes->get('pembayaran/print/(:num)', 'Pembayaran::print/$1');
    $routes->get('pembayaran/detail/(:num)', 'Pembayaran::detail/$1');
    $routes->get('perbaikan/pelanggan/(:any)','Perbaikan::getPelanggan/$1');
    $routes->post('pelanggan/import_excel', 'Pelanggan::importExcel');
    $routes->get('laporan/perbaikan/export','Laporan::exportPerbaikan');
    $routes->get('laporan/keuangan/export','Laporan::exportKeuangan');
    $routes->get('pelanggan/export_template', 'Pelanggan::exportTemplate');
});
