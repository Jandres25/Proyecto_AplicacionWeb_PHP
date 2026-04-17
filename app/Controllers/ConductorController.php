<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Services\ConductorService;
use InvalidArgumentException;

final class ConductorController
{
    private ConductorService $service;

    public function __construct()
    {
        $this->service = new ConductorService();
    }

    public function index(): void
    {
        Auth::requireLogin();
        View::render('conductores/index', [
            'lista_conductores' => $this->service->all(),
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('conductores/create', [
            'old' => ['nombre' => '', 'telefono' => '', 'placa' => ''],
            'taxis' => $this->service->taxiOptions(),
            'error' => '',
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $old = [
            'nombre' => (string) ($_POST['nombre'] ?? ''),
            'telefono' => (string) ($_POST['telefono'] ?? ''),
            'placa' => (string) ($_POST['placa'] ?? ''),
        ];

        try {
            $this->service->create($old['nombre'], $old['telefono'], (int) $old['placa']);
            Flash::set('success', 'Registro Agregado');
            header('Location: ' . app_url('/conductores'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('conductores/create', [
                'old' => $old,
                'taxis' => $this->service->taxiOptions(),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $conductor = $this->service->findById($id);

        if ($conductor === null) {
            http_response_code(404);
            exit('Conductor no encontrado.');
        }

        View::render('conductores/edit', [
            'conductor' => $conductor,
            'taxis' => $this->service->taxiOptions(),
            'error' => '',
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $id = (int) ($_POST['id'] ?? 0);
        $conductor = $this->service->findById($id);

        if ($conductor === null) {
            http_response_code(404);
            exit('Conductor no encontrado.');
        }

        $conductor['Nombres'] = (string) ($_POST['nombre'] ?? '');
        $conductor['Telefono'] = (string) ($_POST['telefono'] ?? '');
        $conductor['Placa'] = (int) ($_POST['placa'] ?? 0);

        try {
            $this->service->update($id, $conductor['Nombres'], $conductor['Telefono'], (int) $conductor['Placa']);
            Flash::set('success', 'Registro Actualizado');
            header('Location: ' . app_url('/conductores'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('conductores/edit', [
                'conductor' => $conductor,
                'taxis' => $this->service->taxiOptions(),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $id = (int) ($_POST['id'] ?? 0);
        $current = $this->service->findById($id);

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

        if ($current === null) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Conductor no encontrado.']);
                exit;
            }
            http_response_code(404);
            exit('Conductor no encontrado.');
        }

        $this->service->delete($id);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Registro Eliminado']);
            exit;
        }

        Flash::set('success', 'Registro Eliminado');
        header('Location: ' . app_url('/conductores'));
        exit;
    }
}
