<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const TOKEN_KEY = '_csrf_token';

    public static function token(): string
    {
        Session::start();

        if (!isset($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION[self::TOKEN_KEY];
    }

    public static function validate(string $token): bool
    {
        Session::start();
        $currentToken = $_SESSION[self::TOKEN_KEY] ?? '';

        return is_string($currentToken) && hash_equals($currentToken, $token);
    }

    public static function validateOrFail(string $token): void
    {
        if (!self::validate($token)) {
            http_response_code(419);
            exit('CSRF token inválido.');
        }
    }
}
