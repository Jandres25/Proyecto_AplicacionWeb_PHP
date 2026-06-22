<?php

declare(strict_types=1);

namespace App\Domain\Models;

use DateTimeImmutable;
use InvalidArgumentException;

final class Turno
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $conductorId,
        public readonly int    $placa,
        public readonly string $inicio,
        public readonly string $fin,
    ) {}

    public static function create(int $conductorId, int $placa, string $inicio, string $fin): self
    {
        if ($conductorId <= 0) {
            throw new InvalidArgumentException('El conductor es obligatorio.');
        }

        if ($placa <= 0) {
            throw new InvalidArgumentException('El taxi es obligatorio.');
        }

        $fmt = 'Y-m-d H:i:s';

        $dtInicio = DateTimeImmutable::createFromFormat($fmt, $inicio);
        if ($dtInicio === false) {
            throw new InvalidArgumentException('La fecha/hora de inicio no es válida.');
        }

        $dtFin = DateTimeImmutable::createFromFormat($fmt, $fin);
        if ($dtFin === false) {
            throw new InvalidArgumentException('La fecha/hora de fin no es válida.');
        }

        if ($dtFin <= $dtInicio) {
            throw new InvalidArgumentException('La fecha/hora de fin debe ser posterior al inicio.');
        }

        return new self(id: 0, conductorId: $conductorId, placa: $placa, inicio: $inicio, fin: $fin);
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id:          (int)    $row['id'],
            conductorId: (int)    $row['conductor_id'],
            placa:       (int)    $row['placa'],
            inicio:      (string) $row['inicio'],
            fin:         (string) $row['fin'],
        );
    }
}
