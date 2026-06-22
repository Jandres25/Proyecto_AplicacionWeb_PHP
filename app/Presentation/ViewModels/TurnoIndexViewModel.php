<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Application\Turnos\TurnoRow;

final class TurnoIndexViewModel extends ViewModel
{
    /** @param TurnoRow[] $listaTurnos */
    public function __construct(
        public readonly array $listaTurnos,
    ) {}
}
