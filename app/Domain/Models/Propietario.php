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

    public static function create(string $nombre, string $telefono): self
    {
        $cleanName = trim($nombre);
        $cleanPhone = trim($telefono);

        if ($cleanName === '') {
            throw new \InvalidArgumentException('El nombre del propietario es obligatorio.');
        }

        if (mb_strlen($cleanName) > 255) {
            throw new \InvalidArgumentException('El nombre excede la longitud permitida.');
        }

        if ($cleanPhone === '') {
            throw new \InvalidArgumentException('El teléfono del propietario es obligatorio.');
        }

        if (!preg_match('/^[0-9]{7,10}$/', $cleanPhone)) {
            throw new \InvalidArgumentException('El teléfono debe contener entre 7 y 10 dígitos.');
        }

        return new self(id: 0, nombre: $cleanName, telefono: $cleanPhone);
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['Idpropietario'],
            nombre: (string) $row['Nombre'],
            telefono: (string) $row['Telefono'],
        );
    }
}
