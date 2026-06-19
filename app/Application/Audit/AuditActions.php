<?php

declare(strict_types=1);

namespace App\Application\Audit;

final class AuditActions
{
    public const AUTH_LOGIN  = 'auth.login';
    public const AUTH_LOGOUT = 'auth.logout';

    public const TAXI_CREATED = 'taxi.created';
    public const TAXI_UPDATED = 'taxi.updated';
    public const TAXI_DELETED = 'taxi.deleted';

    public const PROPIETARIO_CREATED = 'propietario.created';
    public const PROPIETARIO_UPDATED = 'propietario.updated';
    public const PROPIETARIO_DELETED = 'propietario.deleted';

    public const CONDUCTOR_CREATED = 'conductor.created';
    public const CONDUCTOR_UPDATED = 'conductor.updated';
    public const CONDUCTOR_DELETED = 'conductor.deleted';

    public const USUARIO_CREATED = 'usuario.created';
    public const USUARIO_UPDATED = 'usuario.updated';
    public const USUARIO_DELETED = 'usuario.deleted';

    public const PERFIL_UPDATED  = 'perfil.updated';
    public const PERFIL_PASSWORD = 'perfil.password_changed';
}
