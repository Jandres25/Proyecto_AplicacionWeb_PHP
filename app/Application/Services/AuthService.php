<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Application\Contracts\AuthServiceInterface;

final class AuthService implements AuthServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    public function attempt(string $username, string $password): ?array
    {
        $user = $this->users->findByUsername($username);
        if ($user === null) {
            return null;
        }

        $storedPassword = $user->claveHash;
        $isHashed = password_get_info($storedPassword)['algo'] !== null;
        $isValid = $isHashed
            ? password_verify($password, $storedPassword)
            : hash_equals($storedPassword, $password);

        if (!$isValid) {
            return null;
        }

        if (!$isHashed) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $this->users->updatePasswordHash($user->id, $newHash);
        }

        return [
            'ID' => $user->id,
            'Nombres' => $user->nombres,
            'Apellidos' => $user->apellidos,
            'Usuario' => $user->usuario,
            'Correo' => $user->correo,
            'Clave' => $isHashed ? $storedPassword : password_hash($password, PASSWORD_DEFAULT),
        ];
    }
}
