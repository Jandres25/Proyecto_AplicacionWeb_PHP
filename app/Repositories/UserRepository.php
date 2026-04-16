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
}
