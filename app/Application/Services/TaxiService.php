<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Contracts\Repositories\PropietarioRepositoryInterface;
use App\Contracts\Repositories\TaxiRepositoryInterface;
use App\Models\Propietario;
use App\Models\Taxi;
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
        return $this->taxis->allWithOwner();
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
        $data = $this->validate($modelo, $marca, $ownerId);
        $this->taxis->create($data['modelo'], $data['marca'], $data['ownerId']);
    }

    public function update(int $placa, string $modelo, string $marca, int $ownerId): void
    {
        if ($placa <= 0) {
            throw new InvalidArgumentException('Placa inválida.');
        }

        $data = $this->validate($modelo, $marca, $ownerId);
        $this->taxis->update($placa, $data['modelo'], $data['marca'], $data['ownerId']);
    }

    public function delete(int $placa): void
    {
        if ($placa <= 0) {
            throw new InvalidArgumentException('Placa inválida.');
        }

        $this->taxis->delete($placa);
    }

    private function validate(string $modelo, string $marca, int $ownerId): array
    {
        $cleanModel = trim($modelo);
        $cleanBrand = trim($marca);

        if ($cleanModel === '') {
            throw new InvalidArgumentException('El modelo del taxi es obligatorio.');
        }

        if ($cleanBrand === '') {
            throw new InvalidArgumentException('La marca del taxi es obligatoria.');
        }

        if (mb_strlen($cleanModel) > 100 || mb_strlen($cleanBrand) > 100) {
            throw new InvalidArgumentException('Modelo o marca exceden la longitud permitida.');
        }

        if ($ownerId <= 0 || $this->propietarios->findById($ownerId) === null) {
            throw new InvalidArgumentException('Debe seleccionar un propietario válido.');
        }

        return [
            'modelo' => $cleanModel,
            'marca' => $cleanBrand,
            'ownerId' => $ownerId,
        ];
    }
}
