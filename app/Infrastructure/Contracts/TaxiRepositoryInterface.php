<?php

declare(strict_types=1);

namespace App\Infrastructure\Contracts;

use App\Models\Taxi;

interface TaxiRepositoryInterface
{
    /** @return Taxi[] */
    public function allWithOwner(): array;

    /** @return Taxi[] */
    public function all(): array;

    public function findByPlaca(int $placa): ?Taxi;

    public function create(string $modelo, string $marca, int $ownerId): void;

    public function update(int $placa, string $modelo, string $marca, int $ownerId): void;

    public function delete(int $placa): void;
}
