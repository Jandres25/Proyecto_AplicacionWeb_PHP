<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Domain\Models\Propietario;
use App\Domain\Models\Taxi;

final class TaxiEditViewModel extends ViewModel
{
    /** @param Propietario[] $owners */
    public function __construct(
        public readonly Taxi $taxi,
        public readonly array $owners,
        public readonly string $error,
    ) {}
}
