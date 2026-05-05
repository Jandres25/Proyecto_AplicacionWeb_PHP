<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;
use App\Models\Propietario;

final class PropietarioEditViewModel extends ViewModel
{
    public function __construct(
        public readonly Propietario $propietario,
        public readonly string $error,
    ) {}
}
