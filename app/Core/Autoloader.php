<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    private static bool $registered = false;

    /** @var array<string, string> prefix => baseDir */
    private static array $prefixes = [];

    public static function register(): void
    {
        if (self::$registered) {
            return;
        }

        require_once __DIR__ . '/Env.php';
        require_once __DIR__ . '/helpers.php';

        self::$prefixes['App\\'] = dirname(__DIR__);

        spl_autoload_register(static function (string $class): void {
            foreach (self::$prefixes as $prefix => $baseDir) {
                $len = strlen($prefix);
                if (strncmp($class, $prefix, $len) !== 0) {
                    continue;
                }
                $file = $baseDir . '/' . str_replace('\\', '/', substr($class, $len)) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });

        self::$registered = true;
    }

    public static function addNamespace(string $prefix, string $baseDir): void
    {
        self::$prefixes[rtrim($prefix, '\\'). '\\'] = rtrim($baseDir, '/');
    }
}
