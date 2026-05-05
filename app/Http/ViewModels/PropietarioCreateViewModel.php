<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;

final class PropietarioCreateViewModel extends ViewModel
{
    /** @param array<string, string> $old */
    public function __construct(
        public readonly array $old,
        public readonly string $error,
    ) {}
}
