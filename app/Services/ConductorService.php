<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ConductorRepository;
use App\Repositories\TaxiRepository;
use InvalidArgumentException;

final class ConductorService
{
    private ConductorRepository $conductores;
    private TaxiRepository $taxis;

    public function __construct()
    {
        $this->conductores = new ConductorRepository();
        $this->taxis = new TaxiRepository();
    }

    public function all(): array
    {
        return $this->conductores->all();
    }

    public function taxiOptions(): array
    {
        return $this->taxis->all();
    }

    public function findById(int $id): ?array
    {
        return $this->conductores->findById($id);
    }

    public function create(string $nombres, string $telefono, int $placa): void
    {
        $data = $this->validate($nombres, $telefono, $placa);
        $this->conductores->create($data['nombres'], $data['telefono'], $data['placa']);
    }

    public function update(int $id, string $nombres, string $telefono, int $placa): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de conductor inválido.');
        }

        $data = $this->validate($nombres, $telefono, $placa);
        $this->conductores->update($id, $data['nombres'], $data['telefono'], $data['placa']);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de conductor inválido.');
        }

        $this->conductores->delete($id);
    }

    private function validate(string $nombres, string $telefono, int $placa): array
    {
        $cleanName = trim($nombres);
        $cleanPhone = trim($telefono);

        if ($cleanName === '') {
            throw new InvalidArgumentException('El nombre del conductor es obligatorio.');
        }

        if (mb_strlen($cleanName) > 255) {
            throw new InvalidArgumentException('El nombre excede la longitud permitida.');
        }

        if ($cleanPhone === '' || !preg_match('/^[0-9]{7,10}$/', $cleanPhone)) {
            throw new InvalidArgumentException('El teléfono debe contener entre 7 y 10 dígitos.');
        }

        if ($placa <= 0 || $this->taxis->findByPlaca($placa) === null) {
            throw new InvalidArgumentException('Debe seleccionar una placa válida.');
        }

        return [
            'nombres' => $cleanName,
            'telefono' => $cleanPhone,
            'placa' => $placa,
        ];
    }
}
