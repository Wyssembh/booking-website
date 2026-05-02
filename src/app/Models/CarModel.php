<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CarModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(
        string $marque,
        string $modele,
        string $type,
        int $portes,
        string $carburant,
        float $prixParJour,
        ?string $image = null
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO cars (marque, modele, type, portes, carburant, prix_par_jour, image) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([$marque, $modele, $type, $portes, $carburant, $prixParJour, $image]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM cars ORDER BY created_at DESC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM cars WHERE id = ?');
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(
        int $id,
        string $marque,
        string $modele,
        string $type,
        int $portes,
        string $carburant,
        float $prixParJour,
        string $statut,
        ?string $image = null
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE cars SET marque = ?, modele = ?, type = ?, portes = ?, carburant = ?, prix_par_jour = ?, statut = ?, image = ? WHERE id = ?'
        );

        $stmt->execute([$marque, $modele, $type, $portes, $carburant, $prixParJour, $statut, $image, $id]);
    }

    public function delete(int $id): void
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('DELETE FROM car_rentals WHERE car_id = ?');
            $stmt->execute([$id]);
            $stmt = $this->pdo->prepare('DELETE FROM cars WHERE id = ?');
            $stmt->execute([$id]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getAvailableCars(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM cars WHERE statut = ? ORDER BY prix_par_jour ASC');
        $stmt->execute(['disponible']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isAvailableForPeriod(int $carId, string $dateDebut, string $dateFin): bool
    {
        // Chevauchement : location existante [date_debut, date_fin] ∩ [dateDebut, dateFin] ≠ ∅
        // ⟺ exist.date_debut <= dateFin AND exist.date_fin >= dateDebut (bornes DATE inclusives)
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM car_rentals
             WHERE car_id = ? AND statut IN (?, ?, ?)
             AND date_debut <= ? AND date_fin >= ?'
        );

        $stmt->execute([$carId, 'en_attente', 'confirmee', 'terminee', $dateFin, $dateDebut]);

        return (int) $stmt->fetchColumn() === 0;
    }
}