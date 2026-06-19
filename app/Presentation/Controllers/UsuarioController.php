<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use Core\Auth;
use Core\Csrf;
use Core\ErrorHandler;
use Core\Flash;
use Core\View;
use App\Presentation\Http\Request;
use App\Presentation\Http\Response;
use App\Presentation\ViewModels\UsuarioCreateViewModel;
use App\Presentation\ViewModels\UsuarioEditViewModel;
use App\Presentation\ViewModels\UsuarioIndexViewModel;
use App\Application\Audit\AuditActions;
use App\Application\Contracts\AuditServiceInterface;
use App\Application\Services\UsuarioService;
use InvalidArgumentException;

final class UsuarioController
{
    public function __construct(
        private readonly UsuarioService $service,
        private readonly AuditServiceInterface $auditService,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();
        View::renderWith('usuarios/index', new UsuarioIndexViewModel(
            listaUsuarios: $this->service->all(),
        ));
    }

    public function create(): void
    {
        Auth::requireAdmin();
        View::renderWith('usuarios/create', new UsuarioCreateViewModel(
            old: ['nombres' => '', 'apellidos' => '', 'usuario' => '', 'correo' => ''],
            error: '',
        ));
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $old = [
            'nombres' => (string) $this->request->post('nombres', ''),
            'apellidos' => (string) $this->request->post('apellidos', ''),
            'usuario' => (string) $this->request->post('usuario', ''),
            'correo' => (string) $this->request->post('correo', ''),
        ];
        $clave = (string) $this->request->post('clave', '');

        try {
            $this->service->create($old['nombres'], $old['apellidos'], $old['usuario'], $clave, $old['correo']);
            $this->auditService->log(AuditActions::USUARIO_CREATED, 'usuarios', null, "Usuario creado: @{$old['usuario']}");
            Flash::set('success', 'Usuario creado exitosamente');
            Response::redirect(app_url('/usuarios'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('usuarios/create', new UsuarioCreateViewModel(
                old: $old,
                error: $exception->getMessage(),
            ));
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $id = (int) $this->request->get('id', 0);
        $usuario = $this->service->findById($id);

        if ($usuario === null) {
            ErrorHandler::abort(404, 'Usuario no encontrado.');
        }

        View::renderWith('usuarios/edit', new UsuarioEditViewModel(
            usuario: $usuario,
            error: '',
        ));
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = (int) $this->request->post('id', 0);
        $usuario = $this->service->findById($id);

        if ($usuario === null) {
            ErrorHandler::abort(404, 'Usuario no encontrado.');
        }

        $nombres = (string) $this->request->post('nombres', '');
        $apellidos = (string) $this->request->post('apellidos', '');
        $usuarioNombre = (string) $this->request->post('usuario', '');
        $correo = (string) $this->request->post('correo', '');
        $clave = (string) $this->request->post('clave', '');

        try {
            $this->service->update($id, $nombres, $apellidos, $usuarioNombre, $clave, $correo);
            $this->auditService->log(AuditActions::USUARIO_UPDATED, 'usuarios', (string) $id, "Usuario actualizado: @{$usuarioNombre} (id #{$id})");
            Flash::set('success', 'Usuario actualizado exitosamente');
            Response::redirect(app_url('/usuarios'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('usuarios/edit', new UsuarioEditViewModel(
                usuario: $usuario,
                error: $exception->getMessage(),
            ));
        }
    }

    public function destroy(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = (int) $this->request->post('id', 0);
        $current = $this->service->findById($id);

        if ($current === null) {
            if ($this->request->isAjax()) {
                Response::json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
                return;
            }
            ErrorHandler::abort(404, 'Usuario no encontrado.');
        }

        if ($current->usuario === Auth::username()) {
            if ($this->request->isAjax()) {
                Response::json(['success' => false, 'message' => 'No puedes eliminar tu propio usuario'], 403);
                return;
            }
            Flash::set('error', 'No puedes eliminar tu propio usuario');
            Response::redirect(app_url('/usuarios'));
        }

        $this->service->delete($id);
        $this->auditService->log(AuditActions::USUARIO_DELETED, 'usuarios', (string) $id, "Usuario eliminado: @{$current->usuario} (id #{$id})");

        if ($this->request->isAjax()) {
            Response::json(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
            return;
        }

        Flash::set('success', 'Usuario eliminado exitosamente');
        Response::redirect(app_url('/usuarios'));
    }
}
