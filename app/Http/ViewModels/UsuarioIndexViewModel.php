<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;
use App\Models\Usuario;

final class UsuarioIndexViewModel extends ViewModel
{
    /** @param Usuario[] $listaUsuarios */
    public function __construct(public readonly array $listaUsuarios) {}
}
