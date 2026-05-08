<?php

declare(strict_types=1);

namespace Core;

final class Config
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return env($key, $default);
    }
}
