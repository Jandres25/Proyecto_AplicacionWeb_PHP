<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Autoloader.php';

App\Core\Autoloader::register();
set_exception_handler([App\Core\ErrorHandler::class, 'handleException']);

$router = new App\Core\Router();

require __DIR__ . '/../routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/',
    $_SERVER['SCRIPT_NAME'] ?? '/index.php'
);
