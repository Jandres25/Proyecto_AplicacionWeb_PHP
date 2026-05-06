<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Autoloader.php';

Core\Autoloader::register();
Core\Autoloader::addNamespace('Core\\', __DIR__ . '/../core');

set_exception_handler([Core\ErrorHandler::class, 'handleException']);

$container = new Core\Container();
(require __DIR__ . '/../config/bindings.php')($container);

$router = new Core\Router($container);

require __DIR__ . '/../routes/web.php';

return $router;
