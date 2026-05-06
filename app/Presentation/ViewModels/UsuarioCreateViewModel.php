<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;

final class UsuarioCreateViewModel extends ViewModel
{
    /** @param array<string, string> $old */
    public function __construct(
        public readonly array $old,
        public readonly string $error,
    ) {}
}
