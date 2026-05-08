<?php

declare(strict_types=1);

namespace Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $lifetime = (int) ($_ENV['SESSION_LIFETIME'] ?? 120) * 60;
            ini_set('session.gc_maxlifetime', (string) $lifetime);
            session_set_cookie_params(['lifetime' => $lifetime]);
            session_start();
        }
    }
}
