<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Services\TaxiService;
use InvalidArgumentException;

final class TaxiController
{
    private TaxiService $service;

    public function __construct()
    {
        $this->service = new TaxiService();
    }

    public function index(): void
    {
        Auth::requireAdmin();

        View::render('taxis/index', [
            'lista_taxis' => $this->service->allWithOwner(),
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();

        View::render('taxis/create', [
            'old' => ['modelo' => '', 'marca' => '', 'propietario' => ''],
            'owners' => $this->service->ownerOptions(),
            'error' => '',
        ]);
    }

    public function store(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $old = [
            'modelo' => (string) ($_POST['modelo'] ?? ''),
            'marca' => (string) ($_POST['marca'] ?? ''),
            'propietario' => (string) ($_POST['propietario'] ?? ''),
        ];

        try {
            $this->service->create($old['modelo'], $old['marca'], (int) $old['propietario']);
            Flash::set('success', 'Registro Agregado');
            header('Location: ' . app_url('/taxis'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('taxis/create', [
                'old' => $old,
                'owners' => $this->service->ownerOptions(),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function edit(): void
    {
        Auth::requireAdmin();

        $placa = (int) ($_GET['placa'] ?? 0);
        $taxi = $this->service->findByPlaca($placa);

        if ($taxi === null) {
            http_response_code(404);
            exit('Taxi no encontrado.');
        }

        View::render('taxis/edit', [
            'taxi' => $taxi,
            'owners' => $this->service->ownerOptions(),
            'error' => '',
        ]);
    }

    public function update(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $placa = (int) ($_POST['placa'] ?? 0);
        $taxi = $this->service->findByPlaca($placa);
        if ($taxi === null) {
            http_response_code(404);
            exit('Taxi no encontrado.');
        }

        $taxi['Modelo'] = (string) ($_POST['modelo'] ?? '');
        $taxi['Marca'] = (string) ($_POST['marca'] ?? '');
        $taxi['Idpropietario'] = (int) ($_POST['propietario'] ?? 0);

        try {
            $this->service->update($placa, $taxi['Modelo'], $taxi['Marca'], (int) $taxi['Idpropietario']);
            Flash::set('success', 'Registro Actualizado');
            header('Location: ' . app_url('/taxis'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('taxis/edit', [
                'taxi' => $taxi,
                'owners' => $this->service->ownerOptions(),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function destroy(): void
    {
        Auth::requireAdmin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $placa = (int) ($_POST['placa'] ?? 0);
        $current = $this->service->findByPlaca($placa);

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

        if ($current === null) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Taxi no encontrado.']);
                exit;
            }
            http_response_code(404);
            exit('Taxi no encontrado.');
        }

        $this->service->delete($placa);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Registro Eliminado']);
            exit;
        }

        Flash::set('success', 'Registro Eliminado');
        header('Location: ' . app_url('/taxis'));
        exit;
    }
}
