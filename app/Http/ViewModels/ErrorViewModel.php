<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;

final class ErrorViewModel extends ViewModel
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $title,
        public readonly string $message,
    ) {}
}
