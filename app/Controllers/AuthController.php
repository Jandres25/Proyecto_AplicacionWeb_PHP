<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\AuthServiceInterface;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;

final class AuthController
{
    public function __construct(private readonly AuthServiceInterface $service) {}

    public function showLogin(): void
    {
        if (Auth::check()) {
            header('Location: ' . app_url('/'));
            exit;
        }

        View::render('auth/login', ['mensaje' => $_GET['error'] ?? null], false);
    }

    public function login(): void
    {
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $username = trim((string) ($_POST['usuario'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = $this->service->attempt($username, $password);

        if ($user === null) {
            View::render('auth/login', ['mensaje' => 'Error: El usuario o contraseña son incorrectos'], false);
            return;
        }

        Auth::login($user);
        header('Location: ' . app_url('/'));
        exit;
    }

    public function logout(): void
    {
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));
        Auth::logout();
        header('Location: ' . app_url('/login'));
        exit;
    }
}
