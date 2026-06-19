<?php

declare(strict_types=1);

namespace App\Presentation\Http;

final class Request implements IpProviderInterface
{
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function method(): string
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function ip(): ?string
    {
        $addr = $_SERVER['REMOTE_ADDR'] ?? null;
        return $addr !== '' ? $addr : null;
    }
}
