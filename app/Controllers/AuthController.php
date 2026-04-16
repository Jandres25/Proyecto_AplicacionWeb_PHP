<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Services\AuthService;

final class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            header('Location: /Proyecto_AplicacionWeb_PHP/');
            exit;
        }

        View::render('auth/login', ['mensaje' => $_GET['error'] ?? null], false);
    }

    public function login(): void
    {
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $username = trim((string) ($_POST['usuario'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $service = new AuthService();
        $user = $service->attempt($username, $password);

        if ($user === null) {
            View::render('auth/login', ['mensaje' => 'Error: El usuario o contraseña son incorrectos'], false);
            return;
        }

        Auth::login($user);
        header('Location: /Proyecto_AplicacionWeb_PHP/');
        exit;
    }

    public function logout(): void
    {
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));
        Auth::logout();
        header('Location: /Proyecto_AplicacionWeb_PHP/login.php');
        exit;
    }
}
