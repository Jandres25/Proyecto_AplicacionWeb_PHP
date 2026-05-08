<?php

declare(strict_types=1);

namespace Core;

use App\Application\Contracts\AuthServiceInterface;

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
        session_regenerate_id(true);
        $_SESSION['logueado'] = true;
        $_SESSION['user_id']  = (int) ($user['ID'] ?? 0);
        $_SESSION['usuario']  = (string) ($user['Usuario'] ?? '');
        $_SESSION['Nombres']  = (string) ($user['Nombres'] ?? '');
    }

    public static function logout(): void
    {
        Session::start();

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId > 0) {
            self::service()->clearRememberToken($userId);
        }

        self::clearRememberCookie();

        $_SESSION = [];
        session_destroy();
    }

    public static function requireLogin(): void
    {
        if (self::check()) {
            return;
        }

        if (self::attemptRememberLogin()) {
            return;
        }

        header('Location: ' . app_url('/login'));
        exit;
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (self::username() !== 'Administrador') {
            ErrorHandler::abort(403);
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

    public static function issueRememberCookie(int $userId): void
    {
        if (!self::isRememberMeEnabled()) {
            return;
        }

        $plain = self::service()->issueRememberToken($userId);
        self::sendCookie($plain, time() + self::cookieTtl());
    }

    private static function attemptRememberLogin(): bool
    {
        if (!self::isRememberMeEnabled()) {
            return false;
        }

        $cookieName = self::cookieName();
        if (empty($_COOKIE[$cookieName])) {
            return false;
        }

        $result = self::service()->consumeRememberToken((string) $_COOKIE[$cookieName]);

        if ($result === null) {
            self::clearRememberCookie();
            return false;
        }

        self::login($result['user']);
        self::sendCookie($result['newPlainToken'], time() + self::cookieTtl());

        return true;
    }

    private static function clearRememberCookie(): void
    {
        $name = self::cookieName();
        self::sendCookie('', time() - 3600);
        unset($_COOKIE[$name]);
    }

    private static function sendCookie(string $value, int $expires): void
    {
        setcookie(self::cookieName(), $value, [
            'expires'  => $expires,
            'path'     => '/',
            'secure'   => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }

    private static function isRememberMeEnabled(): bool
    {
        return filter_var($_ENV['REMEMBER_ME_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN);
    }

    private static function cookieName(): string
    {
        return (string) ($_ENV['REMEMBER_ME_COOKIE_NAME'] ?? 'remember_token');
    }

    private static function cookieTtl(): int
    {
        $days = (int) ($_ENV['REMEMBER_ME_TTL_DAYS'] ?? 30);
        return ($days > 0 ? $days : 30) * 24 * 60 * 60;
    }

    private static function service(): AuthServiceInterface
    {
        return Container::getInstance()->make(AuthServiceInterface::class);
    }
}
