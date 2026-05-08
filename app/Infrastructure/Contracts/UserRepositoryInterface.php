<?php

declare(strict_types=1);

namespace App\Infrastructure\Contracts;

use App\Domain\Models\Usuario;

interface UserRepositoryInterface
{
    /** @return Usuario[] */
    public function all(): array;

    public function findById(int $id): ?Usuario;

    public function findByUsername(string $username): ?Usuario;

    public function create(string $nombres, string $apellidos, string $usuario, string $claveHash, string $correo): void;

    public function update(int $id, string $nombres, string $apellidos, string $usuario, string $correo): void;

    public function updatePasswordHash(int $id, string $hashedPassword): void;

    public function delete(int $id): void;

    public function existsUsername(string $username, ?int $exceptId = null): bool;

    public function existsEmail(string $email, ?int $exceptId = null): bool;

    public function findByRememberToken(string $hashedToken): ?Usuario;

    public function updateRememberToken(int $userId, ?string $hashedToken, ?string $expiresAt): void;
}
