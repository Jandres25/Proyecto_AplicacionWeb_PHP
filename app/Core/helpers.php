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
        $base = app_base_url();
        $normalizedPath = '/' . ltrim($path, '/');

        if ($normalizedPath === '/') {
            return ($base === '' ? '' : $base) . '/';
        }

        return ($base === '' ? '' : $base) . $normalizedPath;
    }
}
