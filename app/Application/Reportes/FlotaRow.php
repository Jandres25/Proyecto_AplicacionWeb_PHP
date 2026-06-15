<?php

declare(strict_types=1);

namespace App\Application\Reportes;

final class FlotaRow
{
    public function __construct(
        public readonly int $placa,
        public readonly string $marca,
        public readonly string $modelo,
        public readonly string $nombrePropietario,
        public readonly ?string $nombreConductor,
    ) {}
}
