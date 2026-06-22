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
use App\Presentation\ViewModels\TurnoCreateViewModel;
use App\Presentation\ViewModels\TurnoEditViewModel;
use App\Presentation\ViewModels\TurnoIndexViewModel;
use App\Application\Audit\AuditActions;
use App\Application\Contracts\AuditServiceInterface;
use App\Application\Services\TurnoService;
use InvalidArgumentException;

final class TurnoController
{
    public function __construct(
        private readonly TurnoService $service,
        private readonly AuditServiceInterface $auditService,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();
        View::renderWith('turnos/index', new TurnoIndexViewModel(
            listaTurnos: $this->service->all(),
        ));
    }

    public function create(): void
    {
        Auth::requireAdmin();
        View::renderWith('turnos/create', new TurnoCreateViewModel(
            conductores: $this->service->conductorOptions(),
            taxis: $this->service->taxiOptions(),
            old: ['conductor_id' => '', 'placa' => '', 'fecha_inicio' => '', 'hora_inicio' => '', 'fecha_fin' => '', 'hora_fin' => ''],
            error: '',
        ));
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $old = [
            'conductor_id' => (string) $this->request->post('conductor_id', ''),
            'placa'        => (string) $this->request->post('placa', ''),
            'fecha_inicio' => (string) $this->request->post('fecha_inicio', ''),
            'hora_inicio'  => (string) $this->request->post('hora_inicio', ''),
            'fecha_fin'    => (string) $this->request->post('fecha_fin', ''),
            'hora_fin'     => (string) $this->request->post('hora_fin', ''),
        ];

        $conductorId = (int) $old['conductor_id'];
        $placa       = (int) $old['placa'];
        $inicio      = trim($old['fecha_inicio'] . ' ' . $old['hora_inicio']) . ':00';
        $fin         = trim($old['fecha_fin']    . ' ' . $old['hora_fin'])    . ':00';

        try {
            $turno = $this->service->create($conductorId, $placa, $inicio, $fin);
            $this->auditService->log(AuditActions::TURNO_CREATED, 'turnos', (string) $turno->id, "Turno creado: conductor #{$conductorId}, taxi {$placa}, {$inicio} — {$fin}");
            Flash::set('success', 'Turno creado exitosamente');
            Response::redirect(app_url('/turnos'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('turnos/create', new TurnoCreateViewModel(
                conductores: $this->service->conductorOptions(),
                taxis: $this->service->taxiOptions(),
                old: $old,
                error: $exception->getMessage(),
            ));
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $id    = (int) $this->request->get('id', 0);
        $turno = $this->service->findById($id);

        if ($turno === null) {
            ErrorHandler::abort(404, 'Turno no encontrado.');
        }

        View::renderWith('turnos/edit', new TurnoEditViewModel(
            turno: $turno,
            conductores: $this->service->conductorOptions(),
            taxis: $this->service->taxiOptions(),
            error: '',
        ));
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id    = (int) $this->request->post('id', 0);
        $turno = $this->service->findById($id);

        if ($turno === null) {
            ErrorHandler::abort(404, 'Turno no encontrado.');
        }

        $conductorId = (int) $this->request->post('conductor_id', 0);
        $placa       = (int) $this->request->post('placa', 0);
        $inicio      = trim((string) $this->request->post('fecha_inicio', '') . ' ' . (string) $this->request->post('hora_inicio', '')) . ':00';
        $fin         = trim((string) $this->request->post('fecha_fin', '')    . ' ' . (string) $this->request->post('hora_fin', ''))    . ':00';

        try {
            $this->service->update($id, $conductorId, $placa, $inicio, $fin);
            $this->auditService->log(AuditActions::TURNO_UPDATED, 'turnos', (string) $id, "Turno actualizado: conductor #{$conductorId}, taxi {$placa}, {$inicio} — {$fin} (id #{$id})");
            Flash::set('success', 'Turno actualizado exitosamente');
            Response::redirect(app_url('/turnos'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('turnos/edit', new TurnoEditViewModel(
                turno: $turno,
                conductores: $this->service->conductorOptions(),
                taxis: $this->service->taxiOptions(),
                error: $exception->getMessage(),
            ));
        }
    }

    public function destroy(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id      = (int) $this->request->post('id', 0);
        $current = $this->service->findById($id);

        if ($current === null) {
            if ($this->request->isAjax()) {
                Response::json(['success' => false, 'message' => 'Turno no encontrado.'], 404);
                return;
            }
            ErrorHandler::abort(404, 'Turno no encontrado.');
        }

        $this->service->delete($id);
        $this->auditService->log(AuditActions::TURNO_DELETED, 'turnos', (string) $id, "Turno eliminado: conductor #{$current->conductorId}, taxi {$current->placa} (id #{$id})");

        if ($this->request->isAjax()) {
            Response::json(['success' => true, 'message' => 'Turno eliminado exitosamente']);
            return;
        }

        Flash::set('success', 'Turno eliminado exitosamente');
        Response::redirect(app_url('/turnos'));
    }
}
