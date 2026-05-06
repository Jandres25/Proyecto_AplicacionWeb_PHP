<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Domain\Models\Propietario;
use App\Domain\Models\Taxi;
use InvalidArgumentException;

final class TaxiService
{
    public function __construct(
        private readonly TaxiRepositoryInterface $taxis,
        private readonly PropietarioRepositoryInterface $propietarios,
    ) {}

    /** @return Taxi[] */
    public function allWithOwner(): array
    {
        $taxis = $this->taxis->all();
        $propietarios = $this->propietarios->all();

        $nameMap = [];
        foreach ($propietarios as $p) {
            $nameMap[$p->id] = $p->nombre;
        }

        return array_map(
            static fn(Taxi $t) => new Taxi(
                placa: $t->placa,
                modelo: $t->modelo,
                marca: $t->marca,
                idPropietario: $t->idPropietario,
                nombrePropietario: $nameMap[$t->idPropietario] ?? null,
            ),
            $taxis
        );
    }

    /** @return Propietario[] */
    public function ownerOptions(): array
    {
        return $this->propietarios->all();
    }

    public function findByPlaca(int $placa): ?Taxi
    {
        return $this->taxis->findByPlaca($placa);
    }

    public function create(string $modelo, string $marca, int $ownerId): void
    {
        $this->validateOwner($ownerId);
        $taxi = Taxi::create($modelo, $marca, $ownerId);
        $this->taxis->create($taxi->modelo, $taxi->marca, $taxi->idPropietario);
    }

    public function update(int $placa, string $modelo, string $marca, int $ownerId): void
    {
        if ($placa <= 0) {
            throw new InvalidArgumentException('Placa inválida.');
        }

        $this->validateOwner($ownerId);
        $taxi = Taxi::create($modelo, $marca, $ownerId);
        $this->taxis->update($placa, $taxi->modelo, $taxi->marca, $taxi->idPropietario);
    }

    public function delete(int $placa): void
    {
        if ($placa <= 0) {
            throw new InvalidArgumentException('Placa inválida.');
        }

        $this->taxis->delete($placa);
    }

    private function validateOwner(int $ownerId): void
    {
        if ($ownerId <= 0 || $this->propietarios->findById($ownerId) === null) {
            throw new InvalidArgumentException('Debe seleccionar un propietario válido.');
        }
    }
}
