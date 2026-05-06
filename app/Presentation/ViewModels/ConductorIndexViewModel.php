<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Conductor;

final class ConductorIndexViewModel extends ViewModel
{
    /** @param Conductor[] $listaConductores */
    public function __construct(public readonly array $listaConductores) {}
}
