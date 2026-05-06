<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Propietario;

final class PropietarioEditViewModel extends ViewModel
{
    public function __construct(
        public readonly Propietario $propietario,
        public readonly string $error,
    ) {}
}
