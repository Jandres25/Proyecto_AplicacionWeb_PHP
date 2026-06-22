<?php

declare(strict_types=1);

namespace App\Application\Turnos;

final readonly class TurnoRow
{
    public function __construct(
        public int    $id,
        public string $conductorNombre,
        public int    $placa,
        public string $taxiModelo,
        public string $inicio,
        public string $fin,
    ) {}
}
