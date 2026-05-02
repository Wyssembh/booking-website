<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class UserModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $nom, string $prenom, string $email, string $passwordHash): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nom, $prenom, $email, $passwordHash]);
    }
}
