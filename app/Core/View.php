<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    public static function render(string $view, array $data = [], bool $withLayout = true): void
    {
        $basePath = dirname(__DIR__, 2);
        $viewPath = $basePath . '/app/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("La vista {$view} no existe.");
        }

        extract($data, EXTR_SKIP);

        if ($withLayout) {
            require $basePath . '/templates/header.php';
        }

        require $viewPath;

        if ($withLayout) {
            require $basePath . '/templates/footer.php';
        }
    }
}
