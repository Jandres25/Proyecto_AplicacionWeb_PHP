<?php

declare(strict_types=1);

namespace App\Application\Reportes;

final class FiltrosFlota
{
    public function __construct(
        public readonly ?string $marca,
        public readonly ?int $idPropietario,
    ) {}

    public static function fromQuery(?string $marca, ?string $idPropietario): self
    {
        $marcaNorm = ($marca === null || trim($marca) === '') ? null : trim($marca);

        $idPropietarioNorm = null;
        if ($idPropietario !== null && trim($idPropietario) !== '') {
            $parsed = (int) $idPropietario;
            $idPropietarioNorm = $parsed > 0 ? $parsed : null;
        }

        return new self($marcaNorm, $idPropietarioNorm);
    }
}
