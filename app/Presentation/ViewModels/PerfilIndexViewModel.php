<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

final class PerfilIndexViewModel extends ViewModel
{
    public function __construct(
        public readonly string $nombres,
        public readonly string $apellidos,
        public readonly string $usuario,
        public readonly string $correo,
        public readonly string $profileError,
        public readonly string $passwordError,
        public readonly string $activeTab,
    ) {}
}
