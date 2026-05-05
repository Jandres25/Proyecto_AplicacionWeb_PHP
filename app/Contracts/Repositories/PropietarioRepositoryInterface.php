<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Propietario;

interface PropietarioRepositoryInterface
{
    /** @return Propietario[] */
    public function all(): array;

    public function findById(int $id): ?Propietario;

    public function create(string $nombre, string $telefono): void;

    public function update(int $id, string $nombre, string $telefono): void;

    public function delete(int $id): void;
}
