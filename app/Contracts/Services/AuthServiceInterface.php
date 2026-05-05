<?php

declare(strict_types=1);

namespace App\Contracts\Services;

interface AuthServiceInterface
{
    public function attempt(string $username, string $password): ?array;
}
