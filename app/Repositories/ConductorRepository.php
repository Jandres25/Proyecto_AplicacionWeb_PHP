<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ConductorRepositoryInterface;
use App\Core\Database;
use App\Models\Conductor;
use PDO;

final class ConductorRepository implements ConductorRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function all(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM conductores ORDER BY ID DESC');
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM conductores WHERE ID = :ID LIMIT 1');
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row ?: null;
    }

    public function create(string $nombres, string $telefono, int $placa): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO conductores (ID, Nombres, Telefono, Placa) VALUES (null, :Nombres, :Telefono, :Placa)'
        );
        $statement->bindValue(':Nombres', $nombres);
        $statement->bindValue(':Telefono', $telefono);
        $statement->bindValue(':Placa', $placa, PDO::PARAM_INT);
        $statement->execute();
    }

    public function update(int $id, string $nombres, string $telefono, int $placa): void
    {
        $statement = $this->connection->prepare(
            'UPDATE conductores SET Nombres = :Nombres, Telefono = :Telefono, Placa = :Placa WHERE ID = :ID'
        );
        $statement->bindValue(':Nombres', $nombres);
        $statement->bindValue(':Telefono', $telefono);
        $statement->bindValue(':Placa', $placa, PDO::PARAM_INT);
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM conductores WHERE ID = :ID');
        $statement->bindValue(':ID', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /** @return Conductor[] */
    public function allAsModel(): array
    {
        return array_map(
            static fn(array $row) => Conductor::fromRow($row),
            $this->all()
        );
    }

    public function findByIdAsModel(int $id): ?Conductor
    {
        $row = $this->findById($id);
        return $row !== null ? Conductor::fromRow($row) : null;
    }
}
