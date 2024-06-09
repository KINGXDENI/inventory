<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Barang::index');
$routes->get('/barang', 'Barang::barang');
$routes->get('/barang/tambah', 'Barang::tambahbarang');
$routes->get('/barang/edit/(:num)', 'Barang::editbarang/$1');
$routes->post('/barang/simpan', 'Barang::simpan');
$routes->post('/barang/update/(:num)', 'Barang::update/$1');
$routes->get('/barang/delete/(:num)', 'Barang::delete/$1');

$routes->get('/barang-masuk', 'BarangMasuk::index');
$routes->get('/barang-masuk/tambah', 'BarangMasuk::tambah');
$routes->post('/barang-masuk/simpan', 'BarangMasuk::simpan');
$routes->get('/barang-masuk/edit/(:num)', 'BarangMasuk::edit/$1');
$routes->post('/barang-masuk/update/(:num)', 'BarangMasuk::update/$1');
$routes->get('/barang-masuk/hapus/(:num)', 'BarangMasuk::hapus/$1');




