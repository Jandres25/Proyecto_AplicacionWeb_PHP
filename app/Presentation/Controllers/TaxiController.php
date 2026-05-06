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
use App\Presentation\ViewModels\TaxiCreateViewModel;
use App\Presentation\ViewModels\TaxiEditViewModel;
use App\Presentation\ViewModels\TaxiIndexViewModel;
use App\Application\Services\TaxiService;
use InvalidArgumentException;

final class TaxiController
{
    public function __construct(
        private readonly TaxiService $service,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();

        View::renderWith('taxis/index', new TaxiIndexViewModel(
            listaTaxis: $this->service->allWithOwner(),
        ));
    }

    public function create(): void
    {
        Auth::requireAdmin();

        View::renderWith('taxis/create', new TaxiCreateViewModel(
            old: ['modelo' => '', 'marca' => '', 'propietario' => ''],
            owners: $this->service->ownerOptions(),
            error: '',
        ));
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $old = [
            'modelo' => (string) $this->request->post('modelo', ''),
            'marca' => (string) $this->request->post('marca', ''),
            'propietario' => (string) $this->request->post('propietario', ''),
        ];

        try {
            $this->service->create($old['modelo'], $old['marca'], (int) $old['propietario']);
            Flash::set('success', 'Registro Agregado');
            Response::redirect(app_url('/taxis'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('taxis/create', new TaxiCreateViewModel(
                old: $old,
                owners: $this->service->ownerOptions(),
                error: $exception->getMessage(),
            ));
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();

        $placa = (int) $this->request->get('placa', 0);
        $taxi = $this->service->findByPlaca($placa);

        if ($taxi === null) {
            ErrorHandler::abort(404, 'Taxi no encontrado.');
        }

        View::renderWith('taxis/edit', new TaxiEditViewModel(
            taxi: $taxi,
            owners: $this->service->ownerOptions(),
            error: '',
        ));
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $placa = (int) $this->request->post('placa', 0);
        $taxi = $this->service->findByPlaca($placa);
        if ($taxi === null) {
            ErrorHandler::abort(404, 'Taxi no encontrado.');
        }

        $modelo = (string) $this->request->post('modelo', '');
        $marca = (string) $this->request->post('marca', '');
        $idPropietario = (int) $this->request->post('propietario', 0);

        try {
            $this->service->update($placa, $modelo, $marca, $idPropietario);
            Flash::set('success', 'Registro Actualizado');
            Response::redirect(app_url('/taxis'));
        } catch (InvalidArgumentException $exception) {
            View::renderWith('taxis/edit', new TaxiEditViewModel(
                taxi: $taxi,
                owners: $this->service->ownerOptions(),
                error: $exception->getMessage(),
            ));
        }
    }

    public function destroy(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $placa = (int) $this->request->post('placa', 0);
        $current = $this->service->findByPlaca($placa);

        if ($current === null) {
            if ($this->request->isAjax()) {
                Response::json(['success' => false, 'message' => 'Taxi no encontrado.'], 404);
            }
            ErrorHandler::abort(404, 'Taxi no encontrado.');
        }

        $this->service->delete($placa);

        if ($this->request->isAjax()) {
            Response::json(['success' => true, 'message' => 'Registro Eliminado']);
        }

        Flash::set('success', 'Registro Eliminado');
        Response::redirect(app_url('/taxis'));
    }
}
