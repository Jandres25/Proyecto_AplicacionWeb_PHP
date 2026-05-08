<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface AuthServiceInterface
{
    public function attempt(string $username, string $password): ?array;

    public function issueRememberToken(int $userId): string;

    /** @return array{user: array<string,mixed>, newPlainToken: string, expiresAt: string}|null */
    public function consumeRememberToken(string $plainToken): ?array;

    public function clearRememberToken(int $userId): void;
}
