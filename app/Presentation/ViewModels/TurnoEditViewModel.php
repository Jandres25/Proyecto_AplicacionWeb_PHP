<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Domain\Models\Conductor;
use App\Domain\Models\Taxi;
use App\Domain\Models\Turno;

final class TurnoEditViewModel extends ViewModel
{
    /**
     * @param Conductor[] $conductores
     * @param Taxi[]      $taxis
     */
    public function __construct(
        public readonly Turno  $turno,
        public readonly array  $conductores,
        public readonly array  $taxis,
        public readonly string $error,
    ) {}
}
