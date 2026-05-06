<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\ViewModel;

final class ErrorViewModel extends ViewModel
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $title,
        public readonly string $message,
    ) {}
}
