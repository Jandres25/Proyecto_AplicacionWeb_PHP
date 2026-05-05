<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\Services\AuthServiceInterface;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Http\Request;
use App\Http\Response;

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

        View::render('auth/login', ['mensaje' => $this->request->get('error')], false);
    }

    public function login(): void
    {
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $username = trim((string) $this->request->post('usuario', ''));
        $password = (string) $this->request->post('password', '');
        $user = $this->service->attempt($username, $password);

        if ($user === null) {
            View::render('auth/login', ['mensaje' => 'Error: El usuario o contraseña son incorrectos'], false);
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
