<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->addRedirect('/', '/login');
$routes->get('/login', 'UserController::login');
$routes->post('/login', 'UserController::postLogin');
$routes->get('/logout', 'UserController::logout');
$routes->get('/test', 'TestController::index');
