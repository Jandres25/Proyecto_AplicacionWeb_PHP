<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Turnos\TurnoRow;
use App\Domain\Models\Conductor;
use App\Domain\Models\Taxi;
use App\Domain\Models\Turno;
use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Infrastructure\Contracts\TurnoRepositoryInterface;
use InvalidArgumentException;

final class TurnoService
{
    public function __construct(
        private readonly TurnoRepositoryInterface     $turnoRepository,
        private readonly ConductorRepositoryInterface $conductorRepository,
        private readonly TaxiRepositoryInterface      $taxiRepository,
    ) {}

    /** @return TurnoRow[] */
    public function all(): array
    {
        $turnos     = $this->turnoRepository->all();
        $conductores = $this->conductorRepository->all();
        $taxis       = $this->taxiRepository->all();

        $conductorMap = [];
        foreach ($conductores as $c) {
            $conductorMap[$c->id] = $c->nombres;
        }

        $taxiMap = [];
        foreach ($taxis as $t) {
            $taxiMap[$t->placa] = $t->modelo;
        }

        $rows = [];
        foreach ($turnos as $turno) {
            $rows[] = new TurnoRow(
                id: $turno->id,
                conductorNombre: $conductorMap[$turno->conductorId] ?? '',
                placa: $turno->placa,
                taxiModelo: $taxiMap[$turno->placa] ?? '',
                inicio: $turno->inicio,
                fin: $turno->fin,
            );
        }

        return $rows;
    }

    public function findById(int $id): ?Turno
    {
        return $this->turnoRepository->findById($id);
    }

    public function create(int $conductorId, int $placa, string $inicio, string $fin): Turno
    {
        if ($this->conductorRepository->findById($conductorId) === null) {
            throw new InvalidArgumentException('El conductor seleccionado no existe.');
        }

        if ($this->taxiRepository->findByPlaca($placa) === null) {
            throw new InvalidArgumentException('El taxi seleccionado no existe.');
        }

        $turno = Turno::create($conductorId, $placa, $inicio, $fin);

        $this->assertSinSolapamiento($conductorId, $placa, $inicio, $fin);

        return $this->turnoRepository->create($turno->conductorId, $turno->placa, $turno->inicio, $turno->fin);
    }

    public function update(int $id, int $conductorId, int $placa, string $inicio, string $fin): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de turno inválido.');
        }

        if ($this->turnoRepository->findById($id) === null) {
            throw new InvalidArgumentException('El turno no existe.');
        }

        if ($this->conductorRepository->findById($conductorId) === null) {
            throw new InvalidArgumentException('El conductor seleccionado no existe.');
        }

        if ($this->taxiRepository->findByPlaca($placa) === null) {
            throw new InvalidArgumentException('El taxi seleccionado no existe.');
        }

        Turno::create($conductorId, $placa, $inicio, $fin);

        $this->assertSinSolapamiento($conductorId, $placa, $inicio, $fin, $id);

        $this->turnoRepository->update($id, $conductorId, $placa, $inicio, $fin);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de turno inválido.');
        }

        $this->turnoRepository->delete($id);
    }

    /** @return Conductor[] */
    public function conductorOptions(): array
    {
        return $this->conductorRepository->all();
    }

    /** @return Taxi[] */
    public function taxiOptions(): array
    {
        return $this->taxiRepository->all();
    }

    /** @throws InvalidArgumentException si conductor o taxi ya tienen turno solapado */
    private function assertSinSolapamiento(
        int    $conductorId,
        int    $placa,
        string $inicio,
        string $fin,
        ?int   $excludeId = null
    ): void {
        if (count($this->turnoRepository->overlappingForConductor($conductorId, $inicio, $fin, $excludeId)) > 0) {
            throw new InvalidArgumentException('El conductor ya tiene un turno asignado en ese horario.');
        }

        if (count($this->turnoRepository->overlappingForPlaca($placa, $inicio, $fin, $excludeId)) > 0) {
            throw new InvalidArgumentException('El taxi ya está asignado a otro conductor en ese horario.');
        }
    }
}
