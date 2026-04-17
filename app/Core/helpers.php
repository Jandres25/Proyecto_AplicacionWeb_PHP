<?php

declare(strict_types=1);

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('app_base_url')) {
    function app_base_url(): string
    {
        $appUrl = env('APP_URL');
        if ($appUrl !== null) {
            $parsed = parse_url((string) $appUrl);
            return rtrim($parsed['path'] ?? '', '/');
        }

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if ($dir === '' || $dir === '/' || $dir === '.') {
            return '';
        }

        if (str_ends_with($dir, '/public')) {
            $dir = substr($dir, 0, -strlen('/public'));
        }

        return $dir === '' ? '' : $dir;
    }
}

if (!function_exists('app_url')) {
    function app_url(string $path = '/'): string
    {
        $appUrl = env('APP_URL');
        $normalizedPath = '/' . ltrim($path, '/');

        if ($appUrl !== null) {
            return rtrim((string) $appUrl, '/') . $normalizedPath;
        }

        $base = app_base_url();

        if ($normalizedPath === '/') {
            return ($base === '' ? '' : $base) . '/';
        }

        return ($base === '' ? '' : $base) . $normalizedPath;
    }
}
