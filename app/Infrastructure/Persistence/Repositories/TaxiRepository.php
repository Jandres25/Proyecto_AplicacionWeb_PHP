<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Domain\Models\Taxi;
use PDO;

final class TaxiRepository implements TaxiRepositoryInterface
{
    public function __construct(private readonly PDO $connection) {}

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

    public function findByPlaca(int $placa): ?Taxi
    {
        $statement = $this->connection->prepare('SELECT * FROM taxis WHERE Placa = :Placa LIMIT 1');
        $statement->bindValue(':Placa', $placa, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row !== null ? Taxi::fromRow($row) : null;
    }

    /** @return Taxi[] */
    public function all(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM taxis ORDER BY Placa DESC');
        $statement->execute();
        return array_map(
            static fn(array $row) => Taxi::fromRow($row),
            $statement->fetchAll()
        );
    }
}
