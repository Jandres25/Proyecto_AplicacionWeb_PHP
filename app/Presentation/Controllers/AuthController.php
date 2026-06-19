<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Contracts\AuthServiceInterface;
use App\Application\Audit\AuditActions;
use App\Application\Contracts\AuditServiceInterface;
use Core\Auth;
use Core\Csrf;
use Core\View;
use App\Presentation\Http\Request;
use App\Presentation\Http\Response;
use App\Presentation\ViewModels\LoginViewModel;

final class AuthController
{
    public function __construct(
        private readonly AuthServiceInterface $service,
        private readonly AuditServiceInterface $auditService,
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

        if ($this->request->post('remember') === '1') {
            Auth::issueRememberCookie((int) $user['ID']);
        }

        $this->auditService->log(AuditActions::AUTH_LOGIN, 'auth', null, "Login: @{$username}");

        Response::redirect(app_url('/'));
    }

    public function logout(): void
    {
        Csrf::validateOrFail((string) $this->request->post('_token', ''));

        $userId   = Auth::id();
        $username = Auth::username();

        Auth::logout();

        $this->auditService->log(AuditActions::AUTH_LOGOUT, 'auth', (string) $userId, "Logout: @{$username}");

        Response::redirect(app_url('/login'));
    }
}
