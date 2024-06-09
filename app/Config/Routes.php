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


