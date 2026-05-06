<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\ErrorHandler;
use App\Core\Flash;
use App\Core\View;
use App\Presentation\Http\Request;
use App\Presentation\Http\Response;
use App\Presentation\ViewModels\PropietarioCreateViewModel;
use App\Presentation\ViewModels\PropietarioEditViewModel;
use App\Presentation\ViewModels\PropietarioIndexViewModel;
use App\Services\PropietarioService;
use InvalidArgumentException;

final class PropietarioController
{
    public function __construct(
        private readonly PropietarioService $service,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireLogin();

        View::renderWith('propietarios/index', new PropietarioIndexViewModel(
            listaPropietarios: $this->service->all(),
        ));
    }

    public function create(): void
    {
        Auth::requireLogin();

        View::renderWith('propietarios/create', new PropietarioCreateViewModel(
            old: ['nombre' => '', 'telefono' => ''],
            error: '',
        ));
    }

    public function store(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $old = [
            'nombre' => (string) $this->request->post('nombre', ''),
            'telefono' => (string) $this->request->post('telefono', ''),
        ];

        try {
            $this->service->create($old['nombre'], $old['telefono']);
            Flash::set('success', 'Registro Agregado');
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
        Auth::requireLogin();

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
        Auth::requireLogin();
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
            Flash::set('success', 'Registro Actualizado');
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
        Auth::requireLogin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $id = (int) $this->request->post('id', 0);
        $current = $this->service->findById($id);

        if ($current === null) {
            if ($this->request->isAjax()) {
                Response::json(['success' => false, 'message' => 'Propietario no encontrado.'], 404);
            }
            ErrorHandler::abort(404, 'Propietario no encontrado.');
        }

        $this->service->delete($id);

        if ($this->request->isAjax()) {
            Response::json(['success' => true, 'message' => 'Registro Eliminado']);
        }

        Flash::set('success', 'Registro Eliminado');
        Response::redirect(app_url('/propietarios'));
    }
}
