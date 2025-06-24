<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('pengguna/add', 'Pengguna::add');
$routes->post('pengguna/login', 'Pengguna::login');
$routes->post('pengguna/reset', 'Pengguna::resetPassword');
$routes->get('getUserFavorites', 'Favorite::getUserFavorites');
$routes->post('toggleFavorite', 'Favorite::toggleFavorite');
$routes->get('wisata', 'WisataController::index');
$routes->get('wisata/(:num)', 'WisataController::show/$1');
$routes->get('wisata/kategori/(:num)', 'WisataController::byKategori/$1');
$routes->post('wisata', 'WisataController::create');
$routes->get('kategori', 'KategoriController::index');
$routes->get('tampilwisata', 'TampilWisata::index');
$routes->get('tampilwisata/kategori/(:num)', 'TampilWisata::byKategori/$1');
$routes->post('pengajuan', 'PengajuanController::create');
$routes->get('tampilpengajuan', 'PengajuanController::index');
$routes->resource('wisataedit', ['controller' => 'WisataEditController']);
$routes->put('wisataedit/(:num)', 'WisataEditController::update/$1');
$routes->delete('wisataedit/(:num)', 'WisataEditController::delete/$1');
