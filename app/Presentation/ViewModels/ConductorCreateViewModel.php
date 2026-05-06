<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Taxi;

final class ConductorCreateViewModel extends ViewModel
{
    /** @param array<string, string> $old @param Taxi[] $taxis */
    public function __construct(
        public readonly array $old,
        public readonly array $taxis,
        public readonly string $error,
    ) {}
}
