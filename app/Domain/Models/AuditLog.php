<?php

declare(strict_types=1);

namespace App\Domain\Models;

final class AuditLog
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $usuarioId,
        public readonly string $usuarioNombre,
        public readonly string $accion,
        public readonly string $entidad,
        public readonly ?string $entidadId,
        public readonly string $descripcion,
        public readonly ?string $ip,
        public readonly string $creadoEn,
    ) {}

    public static function create(
        ?int $usuarioId,
        string $usuarioNombre,
        string $accion,
        string $entidad,
        ?string $entidadId,
        string $descripcion,
        ?string $ip,
    ): self {
        $accion   = trim($accion);
        $entidad  = trim($entidad);
        $descripcion = trim($descripcion);

        if ($accion === '') {
            throw new \InvalidArgumentException('La acción del log de auditoría es obligatoria.');
        }

        if ($entidad === '') {
            throw new \InvalidArgumentException('La entidad del log de auditoría es obligatoria.');
        }

        if ($descripcion === '') {
            throw new \InvalidArgumentException('La descripción del log de auditoría es obligatoria.');
        }

        return new self(
            id: 0,
            usuarioId: $usuarioId,
            usuarioNombre: trim($usuarioNombre) !== '' ? trim($usuarioNombre) : 'sistema',
            accion: $accion,
            entidad: $entidad,
            entidadId: $entidadId,
            descripcion: $descripcion,
            ip: $ip,
            creadoEn: '',
        );
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            usuarioId: isset($row['usuario_id']) && $row['usuario_id'] !== null ? (int) $row['usuario_id'] : null,
            usuarioNombre: (string) $row['usuario_nombre'],
            accion: (string) $row['accion'],
            entidad: (string) $row['entidad'],
            entidadId: isset($row['entidad_id']) ? (string) $row['entidad_id'] : null,
            descripcion: (string) $row['descripcion'],
            ip: isset($row['ip']) ? (string) $row['ip'] : null,
            creadoEn: (string) $row['creado_en'],
        );
    }
}
