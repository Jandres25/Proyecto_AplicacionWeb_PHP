<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;
use App\Models\Propietario;

final class PropietarioIndexViewModel extends ViewModel
{
    /** @param Propietario[] $listaPropietarios */
    public function __construct(public readonly array $listaPropietarios) {}
}
