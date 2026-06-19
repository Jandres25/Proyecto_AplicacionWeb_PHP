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
use App\Presentation\ViewModels\PropietarioCreateViewModel;
use App\Presentation\ViewModels\PropietarioEditViewModel;
use App\Presentation\ViewModels\PropietarioIndexViewModel;
use App\Application\Audit\AuditActions;
use App\Application\Contracts\AuditServiceInterface;
use App\Application\Services\PropietarioService;
use InvalidArgumentException;

final class PropietarioController
{
    public function __construct(
        private readonly PropietarioService $service,
        private readonly AuditServiceInterface $auditService,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();

        View::renderWith('propietarios/index', new PropietarioIndexViewModel(
            listaPropietarios: $this->service->all(),
        ));
    }

    public function create(): void
    {
        Auth::requireAdmin();

        View::renderWith('propietarios/create', new PropietarioCreateViewModel(
            old: ['nombre' => '', 'telefono' => ''],
            error: '',
        ));
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $old = [
            'nombre' => (string) $this->request->post('nombre', ''),
            'telefono' => (string) $this->request->post('telefono', ''),
        ];

        try {
            $this->service->create($old['nombre'], $old['telefono']);
            $this->auditService->log(AuditActions::PROPIETARIO_CREATED, 'propietarios', null, "Propietario creado: {$old['nombre']}");
            Flash::set('success', 'Propietario creado exitosamente');
            Response::redirect(app_url('/propietarios'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('propietarios/create', new PropietarioCreateViewModel(
                old: $old,
                error: $exception->getMessage(),
            ));
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();

        $id = (int) $this->request->get('id', 0);
        $propietario = $this->service->findById($id);

        if ($propietario === null) {
            ErrorHandler::abort(404, 'Propietario no encontrado.');
        }

        View::renderWith('propietarios/edit', new PropietarioEditViewModel(
            propietario: $propietario,
            error: '',
        ));
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = (int) $this->request->post('id', 0);
        $propietario = $this->service->findById($id);
        if ($propietario === null) {
            ErrorHandler::abort(404, 'Propietario no encontrado.');
        }

        $nombre = (string) $this->request->post('nombre', '');
        $telefono = (string) $this->request->post('telefono', '');

        try {
            $this->service->update($id, $nombre, $telefono);
            $this->auditService->log(AuditActions::PROPIETARIO_UPDATED, 'propietarios', (string) $id, "Propietario actualizado: {$nombre} (id #{$id})");
            Flash::set('success', 'Propietario actualizado exitosamente');
            Response::redirect(app_url('/propietarios'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('propietarios/edit', new PropietarioEditViewModel(
                propietario: $propietario,
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
                Response::json(['success' => false, 'message' => 'Propietario no encontrado.'], 404);
                return;
            }
            ErrorHandler::abort(404, 'Propietario no encontrado.');
        }

        $this->service->delete($id);
        $this->auditService->log(AuditActions::PROPIETARIO_DELETED, 'propietarios', (string) $id, "Propietario eliminado: {$current->nombre} (id #{$id})");

        if ($this->request->isAjax()) {
            Response::json(['success' => true, 'message' => 'Propietario eliminado exitosamente']);
            return;
        }

        Flash::set('success', 'Propietario eliminado exitosamente');
        Response::redirect(app_url('/propietarios'));
    }
}
