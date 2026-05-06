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
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['ID'],
            nombres: (string) $row['Nombres'],
            apellidos: (string) $row['Apellidos'],
            usuario: (string) $row['Usuario'],
            correo: (string) $row['Correo'],
            claveHash: (string) $row['Clave'],
        );
    }
}
