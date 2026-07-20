<?php

use App\Controllers\Auth;
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/dashboard', 'DashboardController::index');

    $routes->get('/prefixes', 'PrefixeController::index');
    $routes->post('/prefixes', 'PrefixeController::store');

    $routes->get('/type-operations', 'TypeOperationController::index');
    $routes->post('/type-operations', 'TypeOperationController::store');
    $routes->get('/type-operations/(:num)/tranches', 'TrancheController::index/$1');
    $routes->post('/type-operations/(:num)/tranches', 'TrancheController::store/$1');
    $routes->get('/tranches/(:num)/edit', 'TrancheController::edit/$1');
    $routes->post('/tranches/(:num)/update', 'TrancheController::update/$1');
    $routes->post('/tranches/(:num)/delete', 'TrancheController::delete/$1');

    $routes->get('/situation-gain', 'SituationGainController::index');

    $routes->get('/situation-client', 'SituationClientController::index');

    $routes->get('/clients/(:num)/historique', 'HistoriqueClientController::index/$1');
});
