<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Infrastructure\Contracts\UserRepositoryInterface;
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

    private function ttlDays(): int
    {
        $val = (int) ($_ENV['REMEMBER_ME_TTL_DAYS'] ?? 30);
        return $val > 0 ? $val : 30;
    }

    public function issueRememberToken(int $userId): string
    {
        $plain   = bin2hex(random_bytes(32));
        $hash    = hash('sha256', $plain);
        $expires = (new \DateTimeImmutable('+' . $this->ttlDays() . ' days'))->format('Y-m-d H:i:s');

        $this->users->updateRememberToken($userId, $hash, $expires);

        return $plain;
    }

    public function consumeRememberToken(string $plainToken): ?array
    {
        if (strlen($plainToken) !== 64 || !ctype_xdigit($plainToken)) {
            return null;
        }

        $hash = hash('sha256', $plainToken);
        $user = $this->users->findByRememberToken($hash);

        if ($user === null) {
            return null;
        }

        $newPlain   = bin2hex(random_bytes(32));
        $newHash    = hash('sha256', $newPlain);
        $newExpires = (new \DateTimeImmutable('+' . $this->ttlDays() . ' days'))->format('Y-m-d H:i:s');

        $this->users->updateRememberToken($user->id, $newHash, $newExpires);

        return [
            'user' => [
                'ID'       => $user->id,
                'Nombres'  => $user->nombres,
                'Apellidos'=> $user->apellidos,
                'Usuario'  => $user->usuario,
                'Correo'   => $user->correo,
            ],
            'newPlainToken' => $newPlain,
            'expiresAt'     => $newExpires,
        ];
    }

    public function clearRememberToken(int $userId): void
    {
        $this->users->updateRememberToken($userId, null, null);
    }
}
