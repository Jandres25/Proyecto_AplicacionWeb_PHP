<?php

declare(strict_types=1);

namespace App\Domain\Models;

final class Conductor
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombres,
        public readonly string $telefono,
        public readonly int $placa,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['ID'],
            nombres: (string) $row['Nombres'],
            telefono: (string) $row['Telefono'],
            placa: (int) $row['Placa'],
        );
    }
}
