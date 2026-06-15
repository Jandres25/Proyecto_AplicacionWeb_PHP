<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Reportes\EstadisticasGenerales;
use App\Application\Reportes\FiltrosFlota;
use App\Application\Reportes\FlotaRow;
use App\Domain\Models\Propietario;
use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;

final class ReporteService
{
    public function __construct(
        private readonly TaxiRepositoryInterface $taxis,
        private readonly PropietarioRepositoryInterface $propietarios,
        private readonly ConductorRepositoryInterface $conductores,
    ) {}

    /** @return FlotaRow[] */
    public function flota(FiltrosFlota $filtros): array
    {
        $taxis       = $this->taxis->all();
        $propietarios = $this->propietarios->all();
        $conductores = $this->conductores->all();

        $propietarioMap = [];
        foreach ($propietarios as $p) {
            $propietarioMap[$p->id] = $p->nombre;
        }

        $conductorMap = [];
        foreach ($conductores as $c) {
            $conductorMap[$c->placa] = $c->nombres;
        }

        $rows = [];
        foreach ($taxis as $taxi) {
            if ($filtros->marca !== null && strcasecmp($taxi->marca, $filtros->marca) !== 0) {
                continue;
            }

            if ($filtros->idPropietario !== null && $taxi->idPropietario !== $filtros->idPropietario) {
                continue;
            }

            $rows[] = new FlotaRow(
                placa: $taxi->placa,
                marca: $taxi->marca,
                modelo: $taxi->modelo,
                nombrePropietario: $propietarioMap[$taxi->idPropietario] ?? '',
                nombreConductor: $conductorMap[$taxi->placa] ?? null,
            );
        }

        return $rows;
    }

    public function estadisticas(): EstadisticasGenerales
    {
        $taxis       = $this->taxis->all();
        $propietarios = $this->propietarios->all();
        $conductores = $this->conductores->all();

        $placasConConductor = array_flip(
            array_map(static fn($c) => $c->placa, $conductores)
        );

        $sinConductor = 0;
        foreach ($taxis as $taxi) {
            if (!isset($placasConConductor[$taxi->placa])) {
                $sinConductor++;
            }
        }

        return new EstadisticasGenerales(
            totalTaxis: count($taxis),
            totalPropietarios: count($propietarios),
            totalConductores: count($conductores),
            taxisSinConductor: $sinConductor,
        );
    }

    /** @return string[] */
    public function marcasDisponibles(): array
    {
        $taxis = $this->taxis->all();

        $marcas = array_unique(
            array_map(static fn($t) => $t->marca, $taxis)
        );

        sort($marcas);

        return array_values($marcas);
    }

    /** @return Propietario[] */
    public function propietarioOptions(): array
    {
        return $this->propietarios->all();
    }
}
