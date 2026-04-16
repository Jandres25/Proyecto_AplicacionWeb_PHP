<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/Core/Autoloader.php';
App\Core\Autoloader::register();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}

App\Core\Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));
App\Core\Auth::logout();
header('Location: ../login.php');
exit;
