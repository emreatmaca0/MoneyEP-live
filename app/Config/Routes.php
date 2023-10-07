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
$routes->post('/edit-account', 'moneyep::edit_account');
$routes->post('/delete-account', 'moneyep::delete_account');
$routes->post('/create-debt', 'moneyep::add_debt');
$routes->post('/edit-debt', 'moneyep::edit_debt');
$routes->post('/delete-debt', 'moneyep::delete_debt');
$routes->post('/create-record', 'Transactions::add_record');
$routes->get('/account-settings','moneyep::account_settings');
