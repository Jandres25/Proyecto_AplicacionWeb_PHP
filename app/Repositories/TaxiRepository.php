<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\TaxiRepositoryInterface;
use App\Core\Database;
use App\Models\Taxi;
use PDO;

final class TaxiRepository implements TaxiRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function allWithOwner(): array
    {
        $statement = $this->connection->prepare(
            'SELECT t.*, p.Nombre AS propietario
             FROM taxis t
             INNER JOIN propietarios p ON p.Idpropietario = t.Idpropietario
             ORDER BY t.Placa DESC'
        );
        $statement->execute();
        return $statement->fetchAll();
    }

    public function all(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM taxis ORDER BY Placa DESC');
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findByPlaca(int $placa): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM taxis WHERE Placa = :Placa LIMIT 1');
        $statement->bindValue(':Placa', $placa, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row ?: null;
    }

    public function create(string $modelo, string $marca, int $ownerId): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO taxis (Placa, Modelo, Marca, Idpropietario) VALUES (null, :Modelo, :Marca, :Idpropietario)'
        );
        $statement->bindValue(':Modelo', $modelo);
        $statement->bindValue(':Marca', $marca);
        $statement->bindValue(':Idpropietario', $ownerId, PDO::PARAM_INT);
        $statement->execute();
    }

    public function update(int $placa, string $modelo, string $marca, int $ownerId): void
    {
        $statement = $this->connection->prepare(
            'UPDATE taxis SET Modelo = :Modelo, Marca = :Marca, Idpropietario = :Idpropietario WHERE Placa = :Placa'
        );
        $statement->bindValue(':Modelo', $modelo);
        $statement->bindValue(':Marca', $marca);
        $statement->bindValue(':Idpropietario', $ownerId, PDO::PARAM_INT);
        $statement->bindValue(':Placa', $placa, PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $placa): void
    {
        $statement = $this->connection->prepare('DELETE FROM taxis WHERE Placa = :Placa');
        $statement->bindValue(':Placa', $placa, PDO::PARAM_INT);
        $statement->execute();
    }

    /** @return Taxi[] */
    public function allWithOwnerAsModel(): array
    {
        return array_map(
            static fn(array $row) => Taxi::fromRow($row),
            $this->allWithOwner()
        );
    }

    public function findByPlacaAsModel(int $placa): ?Taxi
    {
        $row = $this->findByPlaca($placa);
        return $row !== null ? Taxi::fromRow($row) : null;
    }

    /** @return Taxi[] */
    public function allAsModel(): array
    {
        return array_map(
            static fn(array $row) => Taxi::fromRow($row),
            $this->all()
        );
    }
}
