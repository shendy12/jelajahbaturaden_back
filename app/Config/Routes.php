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
$routes->get('review/(:num)', 'ReviewController::index/$1');          
$routes->post('review', 'ReviewController::create');                 
$routes->get('review/rerata/(:num)', 'ReviewController::rerata/$1'); 
$routes->resource('wisataedit', ['controller' => 'WisataEditController']);
$routes->put('wisataedit/(:num)', 'WisataEditController::update/$1');
$routes->delete('wisataedit/(:num)', 'WisataEditController::delete/$1');
$routes->post('pencarian', 'PencarianController::search');
$routes->get('pencarian/history/(:num)', 'PencarianController::history/$1');
$routes->get('pengajuanadmin', 'PengajuanadminController::index');
$routes->get('pengajuanadmin/(:num)', 'PengajuanadminController::show/$1');
$routes->delete('pengajuanadmin/(:num)', 'PengajuanadminController::delete/$1');
$routes->post('pengajuanadmin/posting/(:num)', 'PengajuanadminController::posting/$1');
