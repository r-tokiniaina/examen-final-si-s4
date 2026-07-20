<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->addRedirect('/', '/login');
$routes->get('/login', 'ClientController::login');
$routes->post('/login', 'ClientController::postLogin');
$routes->get('/logout', 'ClientController::logout');

$routes->group('client', function ($routes) {
    $routes->get('/operations', 'ClientController::operations');
    $routes->get('/operations/new', 'ClientController::operationsNew');
    $routes->post('/operations/new', 'ClientController::newOperation');
});

$routes->group('operateur', function ($routes) {
    $routes->get('/login', 'OperateurController::login');
    $routes->post('/login', 'OperateurController::postLogin');
    $routes->get('/dashboard', 'OperateurController::dashboard');
    $routes->get('/prefixes', 'OperateurController::prefixes');
    $routes->post('/prefixes/edit', 'OperateurController::prefixesEdit');
    $routes->get('/frais', 'OperateurController::frais');
    $routes->post('/frais/edit', 'OperateurController::fraisEdit');
});
