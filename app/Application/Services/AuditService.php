<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Contracts\AuditServiceInterface;
use App\Domain\Models\AuditLog;
use App\Infrastructure\Contracts\AuditLogRepositoryInterface;
use App\Presentation\Http\IpProviderInterface;
use Core\Auth;
use Psr\Log\LoggerInterface;

final class AuditService implements AuditServiceInterface
{
    public function __construct(
        private readonly AuditLogRepositoryInterface $repository,
        private readonly IpProviderInterface $ipProvider,
        private readonly LoggerInterface $logger,
    ) {}

    public function log(
        string $accion,
        string $entidad,
        ?string $entidadId,
        string $descripcion,
    ): void {
        try {
            $userId   = Auth::id() ?: null;
            $userName = Auth::username() ?: 'sistema';

            $entry = AuditLog::create(
                usuarioId: $userId,
                usuarioNombre: $userName,
                accion: $accion,
                entidad: $entidad,
                entidadId: $entidadId,
                descripcion: $descripcion,
                ip: $this->ipProvider->ip(),
            );

            $this->repository->insert($entry);
        } catch (\Throwable $e) {
            $this->logger->error('AuditService::log falló', [
                'accion'     => $accion,
                'entidad'    => $entidad,
                'entidad_id' => $entidadId,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
