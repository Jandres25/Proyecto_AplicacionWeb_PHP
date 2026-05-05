<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Autoloader.php';

App\Core\Autoloader::register();
set_exception_handler([App\Core\ErrorHandler::class, 'handleException']);

$container = new App\Core\Container();
(require __DIR__ . '/../app/Providers/bindings.php')($container);

$router = new App\Core\Router($container);

require __DIR__ . '/../routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/',
    $_SERVER['SCRIPT_NAME'] ?? '/index.php'
);
