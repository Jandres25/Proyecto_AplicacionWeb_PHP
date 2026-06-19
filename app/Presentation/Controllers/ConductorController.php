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
use App\Presentation\ViewModels\ConductorCreateViewModel;
use App\Presentation\ViewModels\ConductorEditViewModel;
use App\Presentation\ViewModels\ConductorIndexViewModel;
use App\Application\Audit\AuditActions;
use App\Application\Contracts\AuditServiceInterface;
use App\Application\Services\ConductorService;
use InvalidArgumentException;

final class ConductorController
{
    public function __construct(
        private readonly ConductorService $service,
        private readonly AuditServiceInterface $auditService,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();
        View::renderWith('conductores/index', new ConductorIndexViewModel(
            listaConductores: $this->service->all(),
        ));
    }

    public function create(): void
    {
        Auth::requireAdmin();
        View::renderWith('conductores/create', new ConductorCreateViewModel(
            old: ['nombre' => '', 'telefono' => '', 'placa' => ''],
            taxis: $this->service->taxiOptions(),
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
            'placa' => (string) $this->request->post('placa', ''),
        ];

        try {
            $this->service->create($old['nombre'], $old['telefono'], (int) $old['placa']);
            $this->auditService->log(AuditActions::CONDUCTOR_CREATED, 'conductores', null, "Conductor creado: {$old['nombre']}");
            Flash::set('success', 'Conductor creado exitosamente');
            Response::redirect(app_url('/conductores'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('conductores/create', new ConductorCreateViewModel(
                old: $old,
                taxis: $this->service->taxiOptions(),
                error: $exception->getMessage(),
            ));
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $id = (int) $this->request->get('id', 0);
        $conductor = $this->service->findById($id);

        if ($conductor === null) {
            ErrorHandler::abort(404, 'Conductor no encontrado.');
        }

        View::renderWith('conductores/edit', new ConductorEditViewModel(
            conductor: $conductor,
            taxis: $this->service->taxiOptions(),
            error: '',
        ));
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = (int) $this->request->post('id', 0);
        $conductor = $this->service->findById($id);

        if ($conductor === null) {
            ErrorHandler::abort(404, 'Conductor no encontrado.');
        }

        $nombres = (string) $this->request->post('nombre', '');
        $telefono = (string) $this->request->post('telefono', '');
        $placa = (int) $this->request->post('placa', 0);

        try {
            $this->service->update($id, $nombres, $telefono, $placa);
            $this->auditService->log(AuditActions::CONDUCTOR_UPDATED, 'conductores', (string) $id, "Conductor actualizado: {$nombres} (id #{$id})");
            Flash::set('success', 'Conductor actualizado exitosamente');
            Response::redirect(app_url('/conductores'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('conductores/edit', new ConductorEditViewModel(
                conductor: $conductor,
                taxis: $this->service->taxiOptions(),
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
                Response::json(['success' => false, 'message' => 'Conductor no encontrado.'], 404);
                return;
            }
            ErrorHandler::abort(404, 'Conductor no encontrado.');
        }

        $this->service->delete($id);
        $this->auditService->log(AuditActions::CONDUCTOR_DELETED, 'conductores', (string) $id, "Conductor eliminado: {$current->nombres} (id #{$id})");

        if ($this->request->isAjax()) {
            Response::json(['success' => true, 'message' => 'Conductor eliminado exitosamente']);
            return;
        }

        Flash::set('success', 'Conductor eliminado exitosamente');
        Response::redirect(app_url('/conductores'));
    }
}
