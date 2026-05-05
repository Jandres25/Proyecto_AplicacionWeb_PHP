<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Conductor;

interface ConductorRepositoryInterface
{
    /** @return Conductor[] */
    public function all(): array;

    public function findById(int $id): ?Conductor;

    public function create(string $nombres, string $telefono, int $placa): void;

    public function update(int $id, string $nombres, string $telefono, int $placa): void;

    public function delete(int $id): void;
}
