<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Autoloader.php';

App\Core\Autoloader::register();

$conexion = App\Core\Database::getConnection();
