<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PropietarioRepository;
use InvalidArgumentException;

final class PropietarioService
{
    private PropietarioRepository $propietarios;

    public function __construct()
    {
        $this->propietarios = new PropietarioRepository();
    }

    public function all(): array
    {
        return $this->propietarios->all();
    }

    public function findById(int $id): ?array
    {
        return $this->propietarios->findById($id);
    }

    public function create(string $nombre, string $telefono): void
    {
        $data = $this->validate($nombre, $telefono);
        $this->propietarios->create($data['nombre'], $data['telefono']);
    }

    public function update(int $id, string $nombre, string $telefono): void
    {
        $data = $this->validate($nombre, $telefono);
        $this->propietarios->update($id, $data['nombre'], $data['telefono']);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de propietario inválido.');
        }

        $this->propietarios->delete($id);
    }

    private function validate(string $nombre, string $telefono): array
    {
        $cleanName = trim($nombre);
        $cleanPhone = trim($telefono);

        if ($cleanName === '') {
            throw new InvalidArgumentException('El nombre del propietario es obligatorio.');
        }

        if (mb_strlen($cleanName) > 255) {
            throw new InvalidArgumentException('El nombre excede la longitud permitida.');
        }

        if ($cleanPhone === '') {
            throw new InvalidArgumentException('El teléfono del propietario es obligatorio.');
        }

        if (!preg_match('/^[0-9]{7,10}$/', $cleanPhone)) {
            throw new InvalidArgumentException('El teléfono debe contener entre 7 y 10 dígitos.');
        }

        return [
            'nombre' => $cleanName,
            'telefono' => $cleanPhone,
        ];
    }
}
