<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;

final class LoginViewModel extends ViewModel
{
    public function __construct(public readonly ?string $mensaje) {}
}
