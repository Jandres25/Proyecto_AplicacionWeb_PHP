<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class PropietarioRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function all(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM propietarios ORDER BY Idpropietario DESC');
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM propietarios WHERE Idpropietario = :Idpropietario LIMIT 1');
        $statement->bindValue(':Idpropietario', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row ?: null;
    }

    public function create(string $nombre, string $telefono): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO propietarios (Idpropietario, Nombre, Telefono) VALUES (null, :Nombre, :Telefono)'
        );
        $statement->bindValue(':Nombre', $nombre);
        $statement->bindValue(':Telefono', $telefono);
        $statement->execute();
    }

    public function update(int $id, string $nombre, string $telefono): void
    {
        $statement = $this->connection->prepare(
            'UPDATE propietarios SET Nombre = :Nombre, Telefono = :Telefono WHERE Idpropietario = :Idpropietario'
        );
        $statement->bindValue(':Nombre', $nombre);
        $statement->bindValue(':Telefono', $telefono);
        $statement->bindValue(':Idpropietario', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM propietarios WHERE Idpropietario = :Idpropietario');
        $statement->bindValue(':Idpropietario', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
