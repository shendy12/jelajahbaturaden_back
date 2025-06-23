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

