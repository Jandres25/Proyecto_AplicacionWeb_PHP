<?php

declare(strict_types=1);

$router = require __DIR__ . '/../bootstrap/app.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/',
    $_SERVER['SCRIPT_NAME'] ?? '/index.php'
);
