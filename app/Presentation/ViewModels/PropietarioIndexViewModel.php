<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Propietario;

final class PropietarioIndexViewModel extends ViewModel
{
    /** @param Propietario[] $listaPropietarios */
    public function __construct(public readonly array $listaPropietarios) {}
}
