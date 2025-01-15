<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('register', 'Register::index');
$routes->post('register/submit', 'Register::submit');
$routes->get('login', 'Login::index');
$routes->post('login/submit', 'Login::submit');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('logout', 'Login::logout');
$routes->resource('bookmark');
$routes->get('bookmark', 'Bookmark::index');
$routes->post('bookmark/add', 'Bookmark::add');
$routes->get('bookmark/search/(:segment)', 'Bookmark::searchByTag/$1');
$routes->post('bookmark/edit/(:num)', 'Bookmark::edit/$1');
$routes->delete('bookmark/delete/(:num)', 'Bookmark::delete/$1');
