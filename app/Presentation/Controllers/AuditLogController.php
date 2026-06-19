<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Infrastructure\Contracts\AuditLogRepositoryInterface;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use App\Presentation\Http\Request;
use App\Presentation\ViewModels\AuditLogIndexViewModel;
use Core\Auth;
use Core\View;

final class AuditLogController
{
    public function __construct(
        private readonly AuditLogRepositoryInterface $repository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly Request $request,
    ) {}

    public function index(): void
    {
        Auth::requireAdmin();

        $rawEntidad   = (string) ($this->request->get('entidad') ?? '');
        $rawUsuarioId = (string) ($this->request->get('usuario_id') ?? '');

        $entidad   = $rawEntidad !== '' ? $rawEntidad : null;
        $usuarioId = $rawUsuarioId !== '' ? (int) $rawUsuarioId : null;

        View::renderWith('audit-log/index', new AuditLogIndexViewModel(
            entries: $this->repository->filter($entidad, $usuarioId),
            usuarios: $this->userRepository->all(),
            filtroEntidad: $entidad,
            filtroUsuarioId: $usuarioId,
        ));
    }
}
