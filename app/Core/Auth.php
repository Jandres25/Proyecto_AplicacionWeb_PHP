<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function check(): bool
    {
        Session::start();
        return !empty($_SESSION['logueado']);
    }

    public static function login(array $user): void
    {
        Session::start();
        $_SESSION['logueado'] = true;
        $_SESSION['usuario'] = (string) ($user['Usuario'] ?? '');
        $_SESSION['Nombres'] = (string) ($user['Nombres'] ?? '');
    }

    public static function logout(): void
    {
        Session::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function requireLogin(): void
    {
        if (self::check()) {
            return;
        }

        header('Location: ' . app_url('/login'));
        exit;
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (self::username() !== 'Administrador') {
            http_response_code(403);
            exit('No autorizado.');
        }
    }

    public static function username(): string
    {
        Session::start();
        return (string) ($_SESSION['usuario'] ?? '');
    }

    public static function fullName(): string
    {
        Session::start();
        return (string) ($_SESSION['Nombres'] ?? '');
    }
}
