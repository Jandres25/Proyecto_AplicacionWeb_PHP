<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Domain\Models\AuditLog;
use App\Domain\Models\Usuario;

final class AuditLogIndexViewModel extends ViewModel
{
    /**
     * @param AuditLog[] $entries
     * @param Usuario[]  $usuarios
     */
    public function __construct(
        public readonly array $entries,
        public readonly array $usuarios,
        public readonly ?string $filtroEntidad,
        public readonly ?int $filtroUsuarioId,
    ) {}
}
