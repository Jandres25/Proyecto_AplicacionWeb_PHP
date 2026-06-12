<?php

declare(strict_types=1);

namespace App\Domain\Models;

final class Usuario
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombres,
        public readonly string $apellidos,
        public readonly string $usuario,
        public readonly string $correo,
        public readonly string $claveHash,
        public readonly bool $isAdmin = false,
        public readonly ?string $rememberToken = null,
        public readonly ?string $rememberTokenExpires = null,
    ) {}

    public static function create(
        string $nombres,
        string $apellidos,
        string $usuario,
        string $correo,
    ): self {
        $cleanNames = trim($nombres);
        $cleanLastnames = trim($apellidos);
        $cleanUser = trim($usuario);
        $cleanEmail = trim($correo);

        if ($cleanNames === '' || mb_strlen($cleanNames) > 100) {
            throw new \InvalidArgumentException('Los nombres son obligatorios y deben tener máximo 100 caracteres.');
        }

        if ($cleanLastnames === '' || mb_strlen($cleanLastnames) > 100) {
            throw new \InvalidArgumentException('Los apellidos son obligatorios y deben tener máximo 100 caracteres.');
        }

        if ($cleanUser === '' || mb_strlen($cleanUser) > 50) {
            throw new \InvalidArgumentException('El usuario es obligatorio y debe tener máximo 50 caracteres.');
        }

        if ($cleanEmail === '' || mb_strlen($cleanEmail) > 100 || !filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Debe ingresar un correo válido.');
        }

        return new self(id: 0, nombres: $cleanNames, apellidos: $cleanLastnames, usuario: $cleanUser, correo: $cleanEmail, claveHash: '', isAdmin: false);
    }

    public static function validatePasswordChange(string $nueva, string $confirmacion): void
    {
        if (mb_strlen($nueva) < 8) {
            throw new \InvalidArgumentException('La nueva contraseña debe tener al menos 8 caracteres.');
        }

        if ($nueva !== $confirmacion) {
            throw new \InvalidArgumentException('La nueva contraseña y su confirmación no coinciden.');
        }
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['ID'],
            nombres: (string) $row['Nombres'],
            apellidos: (string) $row['Apellidos'],
            usuario: (string) $row['Usuario'],
            correo: (string) $row['Correo'],
            claveHash: (string) $row['Clave'],
            isAdmin: (bool) ($row['is_admin'] ?? false),
            rememberToken: isset($row['remember_token']) ? (string) $row['remember_token'] : null,
            rememberTokenExpires: isset($row['remember_token_expires']) ? (string) $row['remember_token_expires'] : null,
        );
    }
}
