<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function findByUsername(string $username): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM usuarios WHERE Usuario = :Usuario LIMIT 1');
        $statement->bindValue(':Usuario', $username);
        $statement->execute();

        $user = $statement->fetch();
        return $user ?: null;
    }

    public function updatePasswordHash(int $id, string $hashedPassword): void
    {
        $statement = $this->connection->prepare('UPDATE usuarios SET Clave = :Clave WHERE ID = :ID');
        $statement->bindValue(':Clave', $hashedPassword);
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function all(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM usuarios ORDER BY ID DESC');
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM usuarios WHERE ID = :ID LIMIT 1');
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row ?: null;
    }

    public function create(
        string $nombres,
        string $apellidos,
        string $usuario,
        string $claveHash,
        string $correo
    ): void {
        $statement = $this->connection->prepare(
            'INSERT INTO usuarios (ID, Nombres, Apellidos, Usuario, Clave, Correo)
             VALUES (null, :Nombres, :Apellidos, :Usuario, :Clave, :Correo)'
        );
        $statement->bindValue(':Nombres', $nombres);
        $statement->bindValue(':Apellidos', $apellidos);
        $statement->bindValue(':Usuario', $usuario);
        $statement->bindValue(':Clave', $claveHash);
        $statement->bindValue(':Correo', $correo);
        $statement->execute();
    }

    public function update(
        int $id,
        string $nombres,
        string $apellidos,
        string $usuario,
        string $correo
    ): void {
        $statement = $this->connection->prepare(
            'UPDATE usuarios
             SET Nombres = :Nombres, Apellidos = :Apellidos, Usuario = :Usuario, Correo = :Correo
             WHERE ID = :ID'
        );
        $statement->bindValue(':Nombres', $nombres);
        $statement->bindValue(':Apellidos', $apellidos);
        $statement->bindValue(':Usuario', $usuario);
        $statement->bindValue(':Correo', $correo);
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM usuarios WHERE ID = :ID');
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function existsUsername(string $username, ?int $exceptId = null): bool
    {
        if ($exceptId === null) {
            $statement = $this->connection->prepare('SELECT COUNT(*) FROM usuarios WHERE Usuario = :Usuario');
            $statement->bindValue(':Usuario', $username);
        } else {
            $statement = $this->connection->prepare('SELECT COUNT(*) FROM usuarios WHERE Usuario = :Usuario AND ID != :ID');
            $statement->bindValue(':Usuario', $username);
            $statement->bindValue(':ID', $exceptId, PDO::PARAM_INT);
        }

        $statement->execute();
        return (int) $statement->fetchColumn() > 0;
    }

    public function existsEmail(string $email, ?int $exceptId = null): bool
    {
        if ($exceptId === null) {
            $statement = $this->connection->prepare('SELECT COUNT(*) FROM usuarios WHERE Correo = :Correo');
            $statement->bindValue(':Correo', $email);
        } else {
            $statement = $this->connection->prepare('SELECT COUNT(*) FROM usuarios WHERE Correo = :Correo AND ID != :ID');
            $statement->bindValue(':Correo', $email);
            $statement->bindValue(':ID', $exceptId, PDO::PARAM_INT);
        }

        $statement->execute();
        return (int) $statement->fetchColumn() > 0;
    }
}
