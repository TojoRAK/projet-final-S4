<?php

use App\Controllers\LivresController;
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

$routes->get('/login', 'AuthController::showLogin');
$routes->post('/login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');


