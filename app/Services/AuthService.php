<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

final class AuthService
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function attempt(string $username, string $password): ?array
    {
        $user = $this->users->findByUsername($username);
        if ($user === null) {
            return null;
        }

        $storedPassword = (string) ($user['Clave'] ?? '');
        $isHashed = password_get_info($storedPassword)['algo'] !== null;
        $isValid = $isHashed
            ? password_verify($password, $storedPassword)
            : hash_equals($storedPassword, $password);

        if (!$isValid) {
            return null;
        }

        if (!$isHashed && isset($user['ID'])) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $this->users->updatePasswordHash((int) $user['ID'], $newHash);
            $user['Clave'] = $newHash;
        }

        return $user;
    }
}
