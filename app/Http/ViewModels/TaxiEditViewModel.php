<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;
use App\Models\Propietario;
use App\Models\Taxi;

final class TaxiEditViewModel extends ViewModel
{
    /** @param Propietario[] $owners */
    public function __construct(
        public readonly Taxi $taxi,
        public readonly array $owners,
        public readonly string $error,
    ) {}
}
