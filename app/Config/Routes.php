<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'moneyep::index');
$routes->get('/login', 'moneyep::login');
$routes->post('/login', 'moneyep::login');
$routes->get('/signup', 'moneyep::signup');
$routes->post('/signup', 'moneyep::signup');
$routes->get('/dashboard', 'moneyep::dashboard');
$routes->get('/myassets', 'moneyep::myassets');
$routes->get('/mydebts', 'moneyep::mydebts');
$routes->get('/logout', 'moneyep::logout');
$routes->post('/create-account', 'moneyep::add_account');
