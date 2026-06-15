<?php

declare(strict_types=1);

namespace App\Application\Reportes;

final class EstadisticasGenerales
{
    public function __construct(
        public readonly int $totalTaxis,
        public readonly int $totalPropietarios,
        public readonly int $totalConductores,
        public readonly int $taxisSinConductor,
    ) {}
}
