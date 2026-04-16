<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, callable|array{0: class-string, 1: string}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(string $method, string $uri, string $scriptName): void
    {
        $httpMethod = strtoupper($method);
        $path = $this->resolvePath($uri, $scriptName);
        $handler = $this->routes[$httpMethod][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 - Ruta no encontrada';
            return;
        }

        $this->invoke($handler);
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $normalizedPath = $this->normalizePath($path);
        $this->routes[$method][$normalizedPath] = $handler;
    }

    private function resolvePath(string $uri, string $scriptName): string
    {
        if (isset($_GET['route']) && is_string($_GET['route'])) {
            return $this->normalizePath($_GET['route']);
        }

        $path = parse_url($uri, PHP_URL_PATH);
        $path = is_string($path) ? $path : '/';

        $scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $baseDir = preg_replace('#/public$#', '', $scriptDir) ?: $scriptDir;

        if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
            $path = substr($path, strlen($scriptDir));
        } elseif ($baseDir !== '' && $baseDir !== '/' && str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }

        while (str_starts_with($path, '/public/')) {
            $path = substr($path, strlen('/public'));
        }
        if ($path === '/public') {
            $path = '/';
        }

        $path = preg_replace('#/index\.php$#', '', $path) ?? $path;

        return $this->normalizePath($path);
    }

    private function normalizePath(string $path): string
    {
        $normalized = '/' . trim($path, '/');
        return $normalized === '//' ? '/' : $normalized;
    }

    private function invoke(callable|array $handler): void
    {
        if (is_array($handler) && count($handler) === 2 && is_string($handler[0])) {
            $controller = new $handler[0]();
            $method = $handler[1];
            $controller->$method();
            return;
        }

        $handler();
    }
}
