<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Propietario;

final class TaxiCreateViewModel extends ViewModel
{
    /** @param array<string, string> $old @param Propietario[] $owners */
    public function __construct(
        public readonly array $old,
        public readonly array $owners,
        public readonly string $error,
    ) {}
}
