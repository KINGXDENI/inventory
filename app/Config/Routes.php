<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/logout', 'AuthController::logout');

$routes->group('',['filter' => 'LoggedInFilter'],function ($routes) {
        //auth
        $routes->get('/login', 'AuthController::login');
        $routes->post('/loginProcess', 'AuthController::loginProcess');
    }
);

$routes->group('', ['filter' => 'jabatanFilter'], function ($routes) {
    //barang
    $routes->get('/', 'Barang::index');
    $routes->get('/barang', 'Barang::barang');
    $routes->get('/barang/tambah', 'Barang::tambahbarang');
    $routes->get('/barang/edit/(:num)', 'Barang::editbarang/$1');
    $routes->post('/barang/simpan', 'Barang::simpan');
    $routes->post('/barang/update/(:num)', 'Barang::update/$1');
    $routes->get('/barang/delete/(:num)', 'Barang::delete/$1');

    //barang masuk
    $routes->get('/barang-masuk', 'BarangMasuk::index');
    $routes->get('/barang-masuk/tambah', 'BarangMasuk::tambah');
    $routes->post('/barang-masuk/simpan', 'BarangMasuk::simpan');
    $routes->get('/barang-masuk/edit/(:num)', 'BarangMasuk::edit/$1');
    $routes->post('/barang-masuk/update/(:num)', 'BarangMasuk::update/$1');
    $routes->get('/barang-masuk/hapus/(:num)', 'BarangMasuk::hapus/$1');

    //barang keluar
    $routes->get('/barang-keluar', 'BarangKeluar::index');
    $routes->get('/barang-keluar/tambah', 'BarangKeluar::tambah');
    $routes->post('/barang-keluar/simpan', 'BarangKeluar::simpan');
    $routes->get('/barang-keluar/edit/(:num)', 'BarangKeluar::edit/$1');
    $routes->post('/barang-keluar/update/(:num)', 'BarangKeluar::update/$1');
    $routes->get('/barang-keluar/hapus/(:num)', 'BarangKeluar::hapus/$1');

    //laporan
    $routes->get('/laporan', 'LaporanUmum::index');
    $routes->post('/laporan/generate', 'LaporanUmum::generate');
    $routes->get('/laporan/export/pdf', 'LaporanUmum::exportPdf');
    $routes->get('/laporan/export/excel', 'LaporanUmum::exportExcel');
});
