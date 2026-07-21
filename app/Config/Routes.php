<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->addRedirect('/', '/login');
$routes->get('login', 'ClientController::login', ['filter' => 'auth:bypass,client']);
$routes->post('login', 'ClientController::postLogin');
$routes->get('logout', 'ClientController::logout');

$routes->group('client', ['filter' => 'auth:client'], function ($routes) {
    $routes->get('operations', 'ClientController::operations');
    $routes->post('operations/new', 'ClientController::postOperationsNew');
    $routes->get('operations/calcul-frais', 'ClientController::operationsCalculFrais');

    $routes->get('epargnes', 'ClientController::epargnes');
    $routes->post('epargnes/update', 'ClientController::postEpargnesUpdate');
});


$routes->addRedirect('/admin', '/operateur/login');
$routes->addRedirect('/operateur', '/operateur/login');
$routes->get('/operateur/login', 'OperateurController::login', ['filter' => 'auth:bypass,admin']);
$routes->post('/operateur/login', 'OperateurController::postLogin');

$routes->group('operateur', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', 'OperateurController::dashboard');

    $routes->get('comptes', 'OperateurController::comptes');

    $routes->get('autres-operateurs', 'OperateurController::autresOperateurs');
    $routes->post('autres-operateurs/new', 'OperateurController::postAutresOperateursNew');
    $routes->post('autres-operateurs/(:num)/update', 'OperateurController::postAutresOperateursUpdate/$1');
    $routes->get('autres-operateurs/(:num)/delete', 'OperateurController::autresOperateursDelete/$1');

    $routes->get('prefixes', 'OperateurController::prefixes');
    $routes->post('prefixes/new', 'OperateurController::postPrefixesNew');
    $routes->post('prefixes/(:num)/update', 'OperateurController::postPrefixesUpdate/$1');
    $routes->get('prefixes/(:num)/delete', 'OperateurController::prefixesDelete/$1');

    $routes->get('baremes', 'OperateurController::baremes');
    $routes->post('baremes/new', 'OperateurController::postBaremesNew');
    $routes->post('baremes/(:num)/update', 'OperateurController::postBaremesUpdate/$1');
    $routes->get('baremes/(:num)/delete', 'OperateurController::baremesDelete/$1');
});
