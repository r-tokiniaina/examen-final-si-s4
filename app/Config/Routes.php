<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->addRedirect('/', '/login');
$routes->get('login', 'ClientController::login');
$routes->post('login', 'ClientController::postLogin');
$routes->get('logout', 'ClientController::logout');

// Groupe Espace Client
$routes->group('client', function ($routes) {
    $routes->get('operations', 'ClientController::operations');
    $routes->post('operations/new', 'ClientController::newOperation');
});

// Redirections et Groupe Opérateur / Admin
$routes->addRedirect('/admin', '/operateur/login');
$routes->addRedirect('/operateur', '/operateur/login');
$routes->get('/operateur/login', 'OperateurController::login');
$routes->post('/operateur/login', 'OperateurController::postLogin');

$routes->group('operateur', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'OperateurController::dashboard');
    $routes->get('prefixes', 'OperateurController::prefixes');
    $routes->post('prefixes/new', 'OperateurController::postPrefixesNew');
    $routes->post('prefixes/(:num)/update', 'OperateurController::postPrefixesUpdate/$1');
    $routes->get('prefixes/(:num)/delete', 'OperateurController::prefixesDelete/$1');
    $routes->get('baremes', 'OperateurController::baremes');
    $routes->post('baremes/new', 'OperateurController::postBaremesNew');
    $routes->post('baremes/(:num)/update', 'OperateurController::postBaremesUpdate/$1');
    $routes->get('baremes/(:num)/delete', 'OperateurController::baremesDelete/$1');
});
