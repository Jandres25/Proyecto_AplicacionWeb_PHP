<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use InvalidArgumentException;

final class UsuarioService
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function all(): array
    {
        return $this->users->all();
    }

    public function findById(int $id): ?array
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
        $data = $this->validate($nombres, $apellidos, $usuario, $correo);

        if (trim($clave) === '') {
            throw new InvalidArgumentException('La clave del usuario es obligatoria.');
        }

        if ($this->users->existsUsername($data['usuario'])) {
            throw new InvalidArgumentException('El nombre de usuario ya existe.');
        }

        if ($this->users->existsEmail($data['correo'])) {
            throw new InvalidArgumentException('El correo ya está registrado.');
        }

        $this->users->create(
            $data['nombres'],
            $data['apellidos'],
            $data['usuario'],
            password_hash($clave, PASSWORD_DEFAULT),
            $data['correo']
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

        $data = $this->validate($nombres, $apellidos, $usuario, $correo);

        if ($this->users->existsUsername($data['usuario'], $id)) {
            throw new InvalidArgumentException('El nombre de usuario ya existe.');
        }

        if ($this->users->existsEmail($data['correo'], $id)) {
            throw new InvalidArgumentException('El correo ya está registrado.');
        }

        $this->users->update(
            $id,
            $data['nombres'],
            $data['apellidos'],
            $data['usuario'],
            $data['correo']
        );

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

    private function validate(string $nombres, string $apellidos, string $usuario, string $correo): array
    {
        $cleanNames = trim($nombres);
        $cleanLastnames = trim($apellidos);
        $cleanUser = trim($usuario);
        $cleanEmail = trim($correo);

        if ($cleanNames === '' || mb_strlen($cleanNames) > 100) {
            throw new InvalidArgumentException('Los nombres son obligatorios y deben tener máximo 100 caracteres.');
        }

        if ($cleanLastnames === '' || mb_strlen($cleanLastnames) > 100) {
            throw new InvalidArgumentException('Los apellidos son obligatorios y deben tener máximo 100 caracteres.');
        }

        if ($cleanUser === '' || mb_strlen($cleanUser) > 50) {
            throw new InvalidArgumentException('El usuario es obligatorio y debe tener máximo 50 caracteres.');
        }

        if ($cleanEmail === '' || mb_strlen($cleanEmail) > 100 || !filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Debe ingresar un correo válido.');
        }

        return [
            'nombres' => $cleanNames,
            'apellidos' => $cleanLastnames,
            'usuario' => $cleanUser,
            'correo' => $cleanEmail,
        ];
    }
}
