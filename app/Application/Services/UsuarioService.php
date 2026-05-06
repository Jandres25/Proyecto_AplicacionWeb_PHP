<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Infrastructure\Contracts\UserRepositoryInterface;
use App\Domain\Models\Usuario;
use InvalidArgumentException;

final class UsuarioService
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    /** @return Usuario[] */
    public function all(): array
    {
        return $this->users->all();
    }

    public function findById(int $id): ?Usuario
    {
        return $this->users->findById($id);
    }

    public function create(
        string $nombres,
        string $apellidos,
        string $usuario,
        string $clave,
        string $correo
    ): void {
        if (trim($clave) === '') {
            throw new InvalidArgumentException('La clave del usuario es obligatoria.');
        }

        $u = Usuario::create($nombres, $apellidos, $usuario, $correo);

        if ($this->users->existsUsername($u->usuario)) {
            throw new InvalidArgumentException('El nombre de usuario ya existe.');
        }

        if ($this->users->existsEmail($u->correo)) {
            throw new InvalidArgumentException('El correo ya está registrado.');
        }

        $this->users->create(
            $u->nombres,
            $u->apellidos,
            $u->usuario,
            password_hash($clave, PASSWORD_DEFAULT),
            $u->correo
        );
    }

    public function update(
        int $id,
        string $nombres,
        string $apellidos,
        string $usuario,
        string $clave,
        string $correo
    ): void {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de usuario inválido.');
        }

        $u = Usuario::create($nombres, $apellidos, $usuario, $correo);

        if ($this->users->existsUsername($u->usuario, $id)) {
            throw new InvalidArgumentException('El nombre de usuario ya existe.');
        }

        if ($this->users->existsEmail($u->correo, $id)) {
            throw new InvalidArgumentException('El correo ya está registrado.');
        }

        $this->users->update($id, $u->nombres, $u->apellidos, $u->usuario, $u->correo);

        if (trim($clave) !== '') {
            $this->users->updatePasswordHash($id, password_hash($clave, PASSWORD_DEFAULT));
        }
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID de usuario inválido.');
        }

        $this->users->delete($id);
    }
}
