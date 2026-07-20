<?php

use App\Controllers\LivresController;
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */


$routes->get('/login', 'AuthController::showLogin');
$routes->post('/login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->group('client', function ($routes){
    $routes->get('dashboard', 'CompteController::dashboard');
    $routes->get('operation', 'OperationController::index');
    $routes->post('operation', 'OperationController::store');
    $routes->get('historique', 'CompteController::historique');
});

