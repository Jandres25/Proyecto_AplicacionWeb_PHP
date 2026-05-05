<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;

final class LoginViewModel extends ViewModel
{
    public function __construct(public readonly ?string $mensaje) {}
}
