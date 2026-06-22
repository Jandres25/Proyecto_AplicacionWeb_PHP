<?php

declare(strict_types=1);

namespace App\Infrastructure\Contracts;

use App\Domain\Models\Turno;

interface TurnoRepositoryInterface
{
    /** @return Turno[] */
    public function all(): array;

    public function findById(int $id): ?Turno;

    public function create(int $conductorId, int $placa, string $inicio, string $fin): Turno;

    public function update(int $id, int $conductorId, int $placa, string $inicio, string $fin): void;

    public function delete(int $id): void;

    /** @return Turno[] */
    public function overlappingForConductor(int $conductorId, string $inicio, string $fin, ?int $excludeId = null): array;

    /** @return Turno[] */
    public function overlappingForPlaca(int $placa, string $inicio, string $fin, ?int $excludeId = null): array;
}
