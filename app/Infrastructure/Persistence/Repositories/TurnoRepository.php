<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Infrastructure\Contracts\TurnoRepositoryInterface;
use App\Domain\Models\Turno;
use PDO;

final class TurnoRepository implements TurnoRepositoryInterface
{
    public function __construct(private readonly PDO $connection) {}

    /** @return Turno[] */
    public function all(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM turnos ORDER BY id DESC');
        $statement->execute();
        return array_map(
            static fn(array $row) => Turno::fromRow($row),
            $statement->fetchAll()
        );
    }

    public function findById(int $id): ?Turno
    {
        $statement = $this->connection->prepare('SELECT * FROM turnos WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();
        return $row !== false ? Turno::fromRow($row) : null;
    }

    public function create(int $conductorId, int $placa, string $inicio, string $fin): Turno
    {
        $statement = $this->connection->prepare(
            'INSERT INTO turnos (conductor_id, placa, inicio, fin) VALUES (:conductorId, :placa, :inicio, :fin)'
        );
        $statement->bindValue(':conductorId', $conductorId, PDO::PARAM_INT);
        $statement->bindValue(':placa', $placa, PDO::PARAM_INT);
        $statement->bindValue(':inicio', $inicio);
        $statement->bindValue(':fin', $fin);
        $statement->execute();

        $id = (int) $this->connection->lastInsertId();
        return new Turno(id: $id, conductorId: $conductorId, placa: $placa, inicio: $inicio, fin: $fin);
    }

    public function update(int $id, int $conductorId, int $placa, string $inicio, string $fin): void
    {
        $statement = $this->connection->prepare(
            'UPDATE turnos SET conductor_id = :conductorId, placa = :placa, inicio = :inicio, fin = :fin WHERE id = :id'
        );
        $statement->bindValue(':conductorId', $conductorId, PDO::PARAM_INT);
        $statement->bindValue(':placa', $placa, PDO::PARAM_INT);
        $statement->bindValue(':inicio', $inicio);
        $statement->bindValue(':fin', $fin);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM turnos WHERE id = :id');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /** @return Turno[] */
    public function overlappingForConductor(int $conductorId, string $inicio, string $fin, ?int $excludeId = null): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM turnos
             WHERE conductor_id = :conductorId
               AND inicio < :fin
               AND fin > :inicio
               AND (:excludeId IS NULL OR id <> :excludeId)'
        );
        $statement->bindValue(':conductorId', $conductorId, PDO::PARAM_INT);
        $statement->bindValue(':inicio', $inicio);
        $statement->bindValue(':fin', $fin);
        $statement->bindValue(':excludeId', $excludeId, $excludeId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $statement->execute();

        return array_map(
            static fn(array $row) => Turno::fromRow($row),
            $statement->fetchAll()
        );
    }

    /** @return Turno[] */
    public function overlappingForPlaca(int $placa, string $inicio, string $fin, ?int $excludeId = null): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM turnos
             WHERE placa = :placa
               AND inicio < :fin
               AND fin > :inicio
               AND (:excludeId IS NULL OR id <> :excludeId)'
        );
        $statement->bindValue(':placa', $placa, PDO::PARAM_INT);
        $statement->bindValue(':inicio', $inicio);
        $statement->bindValue(':fin', $fin);
        $statement->bindValue(':excludeId', $excludeId, $excludeId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $statement->execute();

        return array_map(
            static fn(array $row) => Turno::fromRow($row),
            $statement->fetchAll()
        );
    }
}
