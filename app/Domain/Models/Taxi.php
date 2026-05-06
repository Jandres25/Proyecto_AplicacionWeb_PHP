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

    public static function create(string $modelo, string $marca, int $idPropietario): self
    {
        $cleanModel = trim($modelo);
        $cleanBrand = trim($marca);

        if ($cleanModel === '') {
            throw new \InvalidArgumentException('El modelo del taxi es obligatorio.');
        }

        if ($cleanBrand === '') {
            throw new \InvalidArgumentException('La marca del taxi es obligatoria.');
        }

        if (mb_strlen($cleanModel) > 100 || mb_strlen($cleanBrand) > 100) {
            throw new \InvalidArgumentException('Modelo o marca exceden la longitud permitida.');
        }

        return new self(placa: 0, modelo: $cleanModel, marca: $cleanBrand, idPropietario: $idPropietario);
    }

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
