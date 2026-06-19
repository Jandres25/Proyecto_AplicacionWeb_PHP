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
use App\Presentation\ViewModels\PerfilIndexViewModel;
use App\Application\Audit\AuditActions;
use App\Application\Contracts\AuditServiceInterface;
use App\Application\Services\PerfilService;
use InvalidArgumentException;

final class PerfilController
{
    public function __construct(
        private readonly PerfilService $service,
        private readonly AuditServiceInterface $auditService,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireLogin();

        $usuario = $this->service->findById(Auth::id());
        if ($usuario === null) {
            ErrorHandler::abort(404);
        }

        View::renderWith('perfil/index', new PerfilIndexViewModel(
            nombres: $usuario->nombres,
            apellidos: $usuario->apellidos,
            usuario: $usuario->usuario,
            correo: $usuario->correo,
            profileError: '',
            passwordError: '',
            activeTab: 'perfil',
        ));
    }

    public function updateProfile(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = Auth::id();
        $nombres = (string) $this->request->post('nombres', '');
        $apellidos = (string) $this->request->post('apellidos', '');
        $correo = (string) $this->request->post('correo', '');

        try {
            $this->service->updateProfile($id, $nombres, $apellidos, $correo);
            $this->auditService->log(AuditActions::PERFIL_UPDATED, 'perfil', (string) $id, "Perfil actualizado: @" . Auth::username() . " (id #{$id})");
            Flash::set('success', 'Información de perfil actualizada exitosamente.');
            Response::redirect(app_url('/perfil'));
        } catch (InvalidArgumentException $e) {
            $usuario = $this->service->findById($id);
            View::renderWith('perfil/index', new PerfilIndexViewModel(
                nombres: $nombres,
                apellidos: $apellidos,
                usuario: $usuario?->usuario ?? '',
                correo: $correo,
                profileError: $e->getMessage(),
                passwordError: '',
                activeTab: 'perfil',
            ));
        }
    }

    public function updatePassword(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = Auth::id();
        $actual = (string) $this->request->post('clave_actual', '');
        $nueva = (string) $this->request->post('clave_nueva', '');
        $confirmacion = (string) $this->request->post('clave_confirmacion', '');

        try {
            $this->service->updatePassword($id, $actual, $nueva, $confirmacion);
            $this->auditService->log(AuditActions::PERFIL_PASSWORD, 'perfil', (string) $id, "Contraseña cambiada: @" . Auth::username() . " (id #{$id})");
            Flash::set('success', 'Contraseña actualizada exitosamente.');
            Response::redirect(app_url('/perfil'));
        } catch (InvalidArgumentException $e) {
            $usuario = $this->service->findById($id);
            View::renderWith('perfil/index', new PerfilIndexViewModel(
                nombres: $usuario?->nombres ?? '',
                apellidos: $usuario?->apellidos ?? '',
                usuario: $usuario?->usuario ?? '',
                correo: $usuario?->correo ?? '',
                profileError: '',
                passwordError: $e->getMessage(),
                activeTab: 'clave',
            ));
        }
    }
}
