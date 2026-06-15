<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Application\Reportes\EstadisticasGenerales;
use App\Application\Reportes\FiltrosFlota;
use App\Application\Reportes\FlotaRow;
use App\Domain\Models\Propietario;

final class ReporteIndexViewModel extends ViewModel
{
    /**
     * @param FlotaRow[]    $flota
     * @param string[]      $marcas
     * @param Propietario[] $propietarios
     */
    public function __construct(
        public readonly EstadisticasGenerales $estadisticas,
        public readonly array $flota,
        public readonly FiltrosFlota $filtros,
        public readonly array $marcas,
        public readonly array $propietarios,
    ) {}
}
