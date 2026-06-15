<?php

declare(strict_types=1);

namespace Core;

use App\Presentation\ViewModels\LayoutViewModel;
use App\Presentation\ViewModels\ViewModel;
use RuntimeException;

final class View
{
    public static function renderWith(string $view, ViewModel $vm, bool $withLayout = true): void
    {
        $basePath = dirname(__DIR__);
        $viewPath = $basePath . '/app/Presentation/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("La vista {$view} no existe.");
        }

        if ($withLayout) {
            $layout = self::buildLayoutViewModel();
            require $basePath . '/app/Presentation/Views/layout/header.php';
        }

        require $viewPath;

        if ($withLayout) {
            require $basePath . '/app/Presentation/Views/layout/footer.php';
        }
    }

    private static function buildLayoutViewModel(): LayoutViewModel
    {
        $baseUrl = app_url('/');
        $navigationItems = [
            ['href' => $baseUrl, 'label' => 'Inicio'],
            ['href' => $baseUrl . 'conductores', 'label' => 'Conductores'],
            ['href' => $baseUrl . 'propietarios', 'label' => 'Propietarios'],
            ['href' => $baseUrl . 'taxis', 'label' => 'Taxis'],
            ['href' => $baseUrl . 'reportes', 'label' => 'Reportes'],
        ];

        if (Auth::isAdmin()) {
            $navigationItems[] = ['href' => $baseUrl . 'usuarios', 'label' => 'Usuarios'];
        }

        if (Auth::check()) {
            $navigationItems[] = ['href' => $baseUrl . 'perfil', 'label' => 'Mi Perfil'];
        }

        $toastMessages = [];
        if (!self::isAjaxRequest()) {
            $success = Flash::get('success');
            $error = Flash::get('error');

            if ($success !== null) {
                $toastMessages[] = ['type' => 'success', 'message' => $success];
            }

            if ($error !== null) {
                $toastMessages[] = ['type' => 'error', 'message' => $error];
            }
        }

        return new LayoutViewModel(
            baseUrl: $baseUrl,
            fullName: Auth::fullName(),
            csrfToken: Csrf::token(),
            currentYear: (int) date('Y'),
            navigationItems: $navigationItems,
            toastMessages: $toastMessages,
        );
    }

    private static function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}
