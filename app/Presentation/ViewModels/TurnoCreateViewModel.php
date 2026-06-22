<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Domain\Models\Conductor;
use App\Domain\Models\Taxi;

final class TurnoCreateViewModel extends ViewModel
{
    /**
     * @param Conductor[] $conductores
     * @param Taxi[]      $taxis
     * @param array<string, string> $old
     */
    public function __construct(
        public readonly array  $conductores,
        public readonly array  $taxis,
        public readonly array  $old,
        public readonly string $error,
    ) {}
}
