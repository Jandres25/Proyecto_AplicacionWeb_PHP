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

    public static function create(string $nombres, string $telefono, int $placa): self
    {
        $cleanName = trim($nombres);
        $cleanPhone = trim($telefono);

        if ($cleanName === '') {
            throw new \InvalidArgumentException('El nombre del conductor es obligatorio.');
        }

        if (mb_strlen($cleanName) > 255) {
            throw new \InvalidArgumentException('El nombre excede la longitud permitida.');
        }

        if ($cleanPhone === '' || !preg_match('/^[0-9]{7,10}$/', $cleanPhone)) {
            throw new \InvalidArgumentException('El teléfono debe contener entre 7 y 10 dígitos.');
        }

        return new self(id: 0, nombres: $cleanName, telefono: $cleanPhone, placa: $placa);
    }

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
