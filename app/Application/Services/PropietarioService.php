<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Domain\Models\Propietario;
use InvalidArgumentException;

final class PropietarioService
{
    public function __construct(private readonly PropietarioRepositoryInterface $propietarios) {}

    /** @return Propietario[] */
    public function all(): array
    {
        return $this->propietarios->all();
    }

    public function findById(int $id): ?Propietario
    {
        return $this->propietarios->findById($id);
    }

    public function create(string $nombre, string $telefono): void
    {
        $p = Propietario::create($nombre, $telefono);
        $this->propietarios->create($p->nombre, $p->telefono);
    }

    public function update(int $id, string $nombre, string $telefono): void
    {
        $p = Propietario::create($nombre, $telefono);
        $this->propietarios->update($id, $p->nombre, $p->telefono);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de propietario inválido.');
        }

        $this->propietarios->delete($id);
    }
}
