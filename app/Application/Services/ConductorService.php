<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Domain\Models\Conductor;
use App\Domain\Models\Taxi;
use InvalidArgumentException;

final class ConductorService
{
    public function __construct(
        private readonly ConductorRepositoryInterface $conductores,
        private readonly TaxiRepositoryInterface $taxis,
    ) {}

    /** @return Conductor[] */
    public function all(): array
    {
        return $this->conductores->all();
    }

    /** @return Taxi[] */
    public function taxiOptions(): array
    {
        return $this->taxis->all();
    }

    public function findById(int $id): ?Conductor
    {
        return $this->conductores->findById($id);
    }

    public function create(string $nombres, string $telefono, int $placa): void
    {
        $this->validatePlaca($placa);
        $c = Conductor::create($nombres, $telefono, $placa);
        $this->conductores->create($c->nombres, $c->telefono, $c->placa);
    }

    public function update(int $id, string $nombres, string $telefono, int $placa): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de conductor inválido.');
        }

        $this->validatePlaca($placa);
        $c = Conductor::create($nombres, $telefono, $placa);
        $this->conductores->update($id, $c->nombres, $c->telefono, $c->placa);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de conductor inválido.');
        }

        $this->conductores->delete($id);
    }

    private function validatePlaca(int $placa): void
    {
        if ($placa <= 0 || $this->taxis->findByPlaca($placa) === null) {
            throw new InvalidArgumentException('Debe seleccionar una placa válida.');
        }
    }
}
