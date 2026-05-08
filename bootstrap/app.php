<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'APP_URL'])->notEmpty();

$container = new Core\Container();
Core\Container::setInstance($container);
(require __DIR__ . '/../config/bindings.php')($container);

Core\ErrorHandler::setLogger($container->make(\Psr\Log\LoggerInterface::class));
set_exception_handler([Core\ErrorHandler::class, 'handleException']);

$router = new Core\Router($container);
require __DIR__ . '/../routes/web.php';

return $router;
