<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

use App\Http\ViewModel;

final class LayoutViewModel extends ViewModel
{
    /**
     * @param array<int, array{href: string, label: string}> $navigationItems
     * @param array<int, array{type: 'success'|'error', message: string}> $toastMessages
     */
    public function __construct(
        public readonly string $baseUrl,
        public readonly string $fullName,
        public readonly string $csrfToken,
        public readonly int $currentYear,
        public readonly array $navigationItems,
        public readonly array $toastMessages,
    ) {}
}
