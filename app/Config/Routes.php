<?php

use App\Controllers\LivresController;
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

$routes->get('/login', 'AuthController::form');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/users/(:num)', 'UserController::show/$1', ['filter' => 'auth']);
$routes->get('/dashboard', 'LivresController::dashboard', ['filter' => 'auth']);

$routes->group('livres', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'LivresController::index');
    $routes->get('create', 'LivresController::create');
    $routes->post('store', 'LivresController::store');
    $routes->get('(:num)', 'LivresController::show/$1');
    $routes->post('delete/(:num)', 'LivresController::delete/$1');
    $routes->post('emprunter/(:num)', 'LivresController::emprunter/$1');
    $routes->post('retourner/(:num)', 'LivresController::retourner/$1');
});



