<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface AuditServiceInterface
{
    public function log(
        string $accion,
        string $entidad,
        ?string $entidadId,
        string $descripcion,
    ): void;
}
