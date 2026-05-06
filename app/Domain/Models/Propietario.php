<?php

declare(strict_types=1);

namespace App\Domain\Models;

final class Propietario
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
        public readonly string $telefono,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['Idpropietario'],
            nombre: (string) $row['Nombre'],
            telefono: (string) $row['Telefono'],
        );
    }
}
