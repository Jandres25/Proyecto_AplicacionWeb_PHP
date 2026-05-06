<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Conductor;
use App\Models\Taxi;

final class ConductorEditViewModel extends ViewModel
{
    /** @param Taxi[] $taxis */
    public function __construct(
        public readonly Conductor $conductor,
        public readonly array $taxis,
        public readonly string $error,
    ) {}
}
