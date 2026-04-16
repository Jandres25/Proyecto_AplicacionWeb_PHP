<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\PropietarioController;
use App\Controllers\TaxiController;
use App\Controllers\ConductorController;
use App\Controllers\UsuarioController;

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/', [HomeController::class, 'index']);
$router->get('/propietarios', [PropietarioController::class, 'index']);
$router->get('/propietarios/crear', [PropietarioController::class, 'create']);
$router->post('/propietarios/crear', [PropietarioController::class, 'store']);
$router->get('/propietarios/editar', [PropietarioController::class, 'edit']);
$router->post('/propietarios/editar', [PropietarioController::class, 'update']);
$router->post('/propietarios/eliminar', [PropietarioController::class, 'destroy']);
$router->get('/taxis', [TaxiController::class, 'index']);
$router->get('/taxis/crear', [TaxiController::class, 'create']);
$router->post('/taxis/crear', [TaxiController::class, 'store']);
$router->get('/taxis/editar', [TaxiController::class, 'edit']);
$router->post('/taxis/editar', [TaxiController::class, 'update']);
$router->post('/taxis/eliminar', [TaxiController::class, 'destroy']);
$router->get('/conductores', [ConductorController::class, 'index']);
$router->get('/conductores/crear', [ConductorController::class, 'create']);
$router->post('/conductores/crear', [ConductorController::class, 'store']);
$router->get('/conductores/editar', [ConductorController::class, 'edit']);
$router->post('/conductores/editar', [ConductorController::class, 'update']);
$router->post('/conductores/eliminar', [ConductorController::class, 'destroy']);
$router->get('/usuarios', [UsuarioController::class, 'index']);
$router->get('/usuarios/crear', [UsuarioController::class, 'create']);
$router->post('/usuarios/crear', [UsuarioController::class, 'store']);
$router->get('/usuarios/editar', [UsuarioController::class, 'edit']);
$router->post('/usuarios/editar', [UsuarioController::class, 'update']);
$router->post('/usuarios/eliminar', [UsuarioController::class, 'destroy']);
