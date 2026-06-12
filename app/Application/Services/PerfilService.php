<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Models\Usuario;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use InvalidArgumentException;

final class PerfilService
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    public function findById(int $id): ?Usuario
    {
        return $this->users->findById($id);
    }

    public function updateProfile(int $id, string $nombres, string $apellidos, string $correo): void
    {
        $existing = $this->users->findById($id);
        if ($existing === null) {
            throw new InvalidArgumentException('Usuario no encontrado.');
        }

        $validated = Usuario::create($nombres, $apellidos, $existing->usuario, $correo);

        if ($this->users->existsEmail($validated->correo, $id)) {
            throw new InvalidArgumentException('El correo ya está registrado por otro usuario.');
        }

        $this->users->update($id, $validated->nombres, $validated->apellidos, $existing->usuario, $validated->correo);
    }

    public function updatePassword(int $id, string $actual, string $nueva, string $confirmacion): void
    {
        $existing = $this->users->findById($id);
        if ($existing === null) {
            throw new InvalidArgumentException('Usuario no encontrado.');
        }

        if (!password_verify($actual, $existing->claveHash)) {
            throw new InvalidArgumentException('La contraseña actual no es correcta.');
        }

        Usuario::validatePasswordChange($nueva, $confirmacion);

        $this->users->updatePasswordHash($id, password_hash($nueva, PASSWORD_DEFAULT));
    }
}
