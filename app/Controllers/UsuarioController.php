<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\ErrorHandler;
use App\Core\Flash;
use App\Core\View;
use App\Services\UsuarioService;
use InvalidArgumentException;

final class UsuarioController
{
    private UsuarioService $service;

    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function index(): void
    {
        Auth::requireAdmin();
        View::render('usuarios/index', [
            'lista_usuarios' => $this->service->all(),
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();
        View::render('usuarios/create', [
            'old' => ['nombres' => '', 'apellidos' => '', 'usuario' => '', 'correo' => ''],
            'error' => '',
        ]);
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $old = [
            'nombres' => (string) ($_POST['nombres'] ?? ''),
            'apellidos' => (string) ($_POST['apellidos'] ?? ''),
            'usuario' => (string) ($_POST['usuario'] ?? ''),
            'correo' => (string) ($_POST['correo'] ?? ''),
        ];
        $clave = (string) ($_POST['clave'] ?? '');

        try {
            $this->service->create($old['nombres'], $old['apellidos'], $old['usuario'], $clave, $old['correo']);
            Flash::set('success', 'Registro Agregado');
            header('Location: ' . app_url('/usuarios'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('usuarios/create', [
                'old' => $old,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $usuario = $this->service->findById($id);

        if ($usuario === null) {
            ErrorHandler::abort(404, 'Usuario no encontrado.');
        }

        View::render('usuarios/edit', [
            'usuario' => $usuario,
            'error' => '',
        ]);
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $id = (int) ($_POST['id'] ?? 0);
        $usuario = $this->service->findById($id);

        if ($usuario === null) {
            ErrorHandler::abort(404, 'Usuario no encontrado.');
        }

        $usuario['Nombres'] = (string) ($_POST['nombres'] ?? '');
        $usuario['Apellidos'] = (string) ($_POST['apellidos'] ?? '');
        $usuario['Usuario'] = (string) ($_POST['usuario'] ?? '');
        $usuario['Correo'] = (string) ($_POST['correo'] ?? '');
        $clave = (string) ($_POST['clave'] ?? '');

        try {
            $this->service->update(
                $id,
                $usuario['Nombres'],
                $usuario['Apellidos'],
                $usuario['Usuario'],
                $clave,
                $usuario['Correo']
            );
            Flash::set('success', 'Registro Actualizado');
            header('Location: ' . app_url('/usuarios'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('usuarios/edit', [
                'usuario' => $usuario,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function destroy(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $id = (int) ($_POST['id'] ?? 0);
        $current = $this->service->findById($id);

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

        if ($current === null) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
                exit;
            }
            ErrorHandler::abort(404, 'Usuario no encontrado.');
        }

        if ((string) $current['Usuario'] === Auth::username()) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propio usuario']);
                exit;
            }
            Flash::set('error', 'No puedes eliminar tu propio usuario');
            header('Location: ' . app_url('/usuarios'));
            exit;
        }

        $this->service->delete($id);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Registro Eliminado']);
            exit;
        }

        Flash::set('success', 'Registro Eliminado');
        header('Location: ' . app_url('/usuarios'));
        exit;
    }
}
