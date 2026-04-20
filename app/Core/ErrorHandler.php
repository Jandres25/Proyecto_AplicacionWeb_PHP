<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

final class ErrorHandler
{
    /** @var array<int, array{title: string, message: string, view: string}> */
    private const ERRORS = [
        403 => [
            'title' => 'Acceso no autorizado',
            'message' => 'No tienes permisos para acceder a este recurso.',
            'view' => 'errors/generic',
        ],
        404 => [
            'title' => 'Página no encontrada',
            'message' => 'La ruta solicitada no existe o fue movida.',
            'view' => 'errors/404',
        ],
        405 => [
            'title' => 'Método no permitido',
            'message' => 'El método HTTP usado no está permitido para esta ruta.',
            'view' => 'errors/generic',
        ],
        419 => [
            'title' => 'Sesión expirada',
            'message' => 'Tu token CSRF no es válido o ya expiró. Intenta nuevamente.',
            'view' => 'errors/generic',
        ],
        500 => [
            'title' => 'Error interno del servidor',
            'message' => 'Ocurrió un error inesperado. Intenta nuevamente en unos minutos.',
            'view' => 'errors/500',
        ],
    ];

    public static function abort(int $statusCode, ?string $message = null): never
    {
        $error = self::ERRORS[$statusCode] ?? self::ERRORS[500];
        $finalMessage = $message ?? $error['message'];

        if (!headers_sent()) {
            http_response_code($statusCode);
        }

        View::render($error['view'], [
            'statusCode' => $statusCode,
            'title' => $error['title'],
            'message' => $finalMessage,
        ], false);
        exit;
    }

    public static function handleException(Throwable $exception): void
    {
        error_log((string) $exception);
        self::abort(500);
    }
}
