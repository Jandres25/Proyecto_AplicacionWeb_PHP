<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\View;
use App\Services\PropietarioService;
use InvalidArgumentException;

final class PropietarioController
{
    private PropietarioService $service;

    public function __construct()
    {
        $this->service = new PropietarioService();
    }

    public function index(): void
    {
        Auth::requireLogin();

        View::render('propietarios/index', [
            'lista_propietarios' => $this->service->all(),
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();

        View::render('propietarios/create', [
            'old' => ['nombre' => '', 'telefono' => ''],
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
        ];

        try {
            $this->service->create($old['nombre'], $old['telefono']);
            Flash::set('success', 'Registro Agregado');
            header('Location: ' . app_url('/propietarios'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('propietarios/create', [
                'old' => $old,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function edit(): void
    {
        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $propietario = $this->service->findById($id);

        if ($propietario === null) {
            http_response_code(404);
            exit('Propietario no encontrado.');
        }

        View::render('propietarios/edit', [
            'propietario' => $propietario,
            'error' => '',
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));

        $id = (int) ($_POST['id'] ?? 0);
        $propietario = $this->service->findById($id);
        if ($propietario === null) {
            http_response_code(404);
            exit('Propietario no encontrado.');
        }

        $propietario['Nombre'] = (string) ($_POST['nombre'] ?? '');
        $propietario['Telefono'] = (string) ($_POST['telefono'] ?? '');

        try {
            $this->service->update($id, $propietario['Nombre'], $propietario['Telefono']);
            Flash::set('success', 'Registro Actualizado');
            header('Location: ' . app_url('/propietarios'));
            exit;
        } catch (InvalidArgumentException $exception) {
            View::render('propietarios/edit', [
                'propietario' => $propietario,
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
                echo json_encode(['success' => false, 'message' => 'Propietario no encontrado.']);
                exit;
            }
            http_response_code(404);
            exit('Propietario no encontrado.');
        }

        $this->service->delete($id);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Registro Eliminado']);
            exit;
        }

        Flash::set('success', 'Registro Eliminado');
        header('Location: ' . app_url('/propietarios'));
        exit;
    }
}
