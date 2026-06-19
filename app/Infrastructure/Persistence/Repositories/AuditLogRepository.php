<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Models\AuditLog;
use App\Infrastructure\Contracts\AuditLogRepositoryInterface;
use PDO;

final class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function __construct(private readonly PDO $connection) {}

    public function insert(AuditLog $log): void
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO audit_log (usuario_id, usuario_nombre, accion, entidad, entidad_id, descripcion, ip)
             VALUES (:usuario_id, :usuario_nombre, :accion, :entidad, :entidad_id, :descripcion, :ip)'
        );
        $stmt->bindValue(':usuario_id', $log->usuarioId, $log->usuarioId !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':usuario_nombre', $log->usuarioNombre);
        $stmt->bindValue(':accion', $log->accion);
        $stmt->bindValue(':entidad', $log->entidad);
        $stmt->bindValue(':entidad_id', $log->entidadId);
        $stmt->bindValue(':descripcion', $log->descripcion);
        $stmt->bindValue(':ip', $log->ip);
        $stmt->execute();
    }

    /** @return AuditLog[] */
    public function filter(?string $entidad, ?int $usuarioId): array
    {
        $conditions = [];
        $params     = [];

        if ($entidad !== null) {
            $conditions[] = 'entidad = :entidad';
            $params[':entidad'] = $entidad;
        }

        if ($usuarioId !== null) {
            $conditions[] = 'usuario_id = :usuario_id';
            $params[':usuario_id'] = $usuarioId;
        }

        $where = $conditions !== [] ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $stmt  = $this->connection->prepare(
            "SELECT * FROM audit_log {$where} ORDER BY creado_en DESC"
        );

        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $stmt->execute();

        return array_map(
            static fn(array $row) => AuditLog::fromRow($row),
            $stmt->fetchAll()
        );
    }
}
