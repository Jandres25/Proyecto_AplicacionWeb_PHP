<?php

declare(strict_types=1);

namespace App\Infrastructure\Contracts;

use App\Domain\Models\AuditLog;

interface AuditLogRepositoryInterface
{
    public function insert(AuditLog $log): void;

    /** @return AuditLog[] */
    public function filter(?string $entidad, ?int $usuarioId): array;
}
