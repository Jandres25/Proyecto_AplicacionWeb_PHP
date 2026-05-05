<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;
use App\Models\Usuario;

final class UsuarioEditViewModel extends ViewModel
{
    public function __construct(
        public readonly Usuario $usuario,
        public readonly string $error,
    ) {}
}
