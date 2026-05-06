<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Taxi;

final class TaxiIndexViewModel extends ViewModel
{
    /** @param Taxi[] $listaTaxis */
    public function __construct(public readonly array $listaTaxis) {}
}
