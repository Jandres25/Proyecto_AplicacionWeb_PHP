<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface AuthServiceInterface
{
    public function attempt(string $username, string $password): ?array;
}
