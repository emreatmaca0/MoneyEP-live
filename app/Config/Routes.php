<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/moneyep', 'moneyep::index');
$routes->get('/moneyep/login', 'moneyep::login');
$routes->post('/moneyep/login', 'moneyep::login');
$routes->get('/moneyep/signup', 'moneyep::signup');
$routes->post('/moneyep/signup', 'moneyep::signup');
$routes->get('/moneyep/dashboard', 'moneyep::dashboard');
$routes->get('/moneyep/myassets', 'moneyep::myassets');
$routes->get('/moneyep/mydebts', 'moneyep::mydebts');
$routes->get('/moneyep/logout', 'moneyep::logout');
$routes->post('/moneyep/create-account', 'moneyep::add_account');
