<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Reportes\FiltrosFlota;
use App\Application\Services\ReporteService;
use App\Presentation\Http\Request;
use App\Presentation\ViewModels\ReporteIndexViewModel;
use Core\Auth;
use Core\View;

final class ReporteController
{
    public function __construct(
        private readonly ReporteService $service,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();

        $filtros = FiltrosFlota::fromQuery(
            $this->request->get('marca'),
            $this->request->get('propietario'),
        );

        View::renderWith('reportes/index', new ReporteIndexViewModel(
            estadisticas: $this->service->estadisticas(),
            flota: $this->service->flota($filtros),
            filtros: $filtros,
            marcas: $this->service->marcasDisponibles(),
            propietarios: $this->service->propietarioOptions(),
        ));
    }
}
