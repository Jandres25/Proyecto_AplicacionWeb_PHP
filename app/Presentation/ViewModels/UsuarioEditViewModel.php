<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;
use App\Models\Usuario;

final class UsuarioEditViewModel extends ViewModel
{
    public function __construct(
        public readonly Usuario $usuario,
        public readonly string $error,
    ) {}
}
