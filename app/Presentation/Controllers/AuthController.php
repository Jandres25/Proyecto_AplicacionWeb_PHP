<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Contracts\Services\AuthServiceInterface;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Presentation\Http\Request;
use App\Presentation\Http\Response;
use App\Presentation\ViewModels\LoginViewModel;

final class AuthController
{
    public function __construct(
        private readonly AuthServiceInterface $service,
        private readonly Request $request,
    ) {}

    public function showLogin(): void
    {
        if (Auth::check()) {
            Response::redirect(app_url('/'));
        }

        View::renderWith('auth/login', new LoginViewModel(
            mensaje: $this->request->get('error'),
        ), false);
    }

    public function login(): void
    {
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $username = trim((string) $this->request->post('usuario', ''));
        $password = (string) $this->request->post('password', '');
        $user = $this->service->attempt($username, $password);

        if ($user === null) {
            View::renderWith('auth/login', new LoginViewModel(
                mensaje: 'Error: El usuario o contraseña son incorrectos',
            ), false);
            return;
        }

        Auth::login($user);
        Response::redirect(app_url('/'));
    }

    public function logout(): void
    {
        Csrf::validateOrFail((string) $this->request->post('_token', ''));
        Auth::logout();
        Response::redirect(app_url('/login'));
    }
}
