<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\AuthController;

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/', [HomeController::class, 'index']);
