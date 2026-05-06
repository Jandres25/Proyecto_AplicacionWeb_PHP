<?php

declare(strict_types=1);

namespace App\Domain\Models;

final class Taxi
{
    public function __construct(
        public readonly int $placa,
        public readonly string $modelo,
        public readonly string $marca,
        public readonly int $idPropietario,
        public readonly ?string $nombrePropietario = null,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            placa: (int) $row['Placa'],
            modelo: (string) $row['Modelo'],
            marca: (string) $row['Marca'],
            idPropietario: (int) $row['Idpropietario'],
            nombrePropietario: isset($row['propietario']) ? (string) $row['propietario'] : null,
        );
    }
}
