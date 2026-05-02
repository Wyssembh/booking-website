<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CarRentalModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(
        int $userId,
        int $carId,
        string $dateDebut,
        string $dateFin,
        float $prixTotal
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO car_rentals (user_id, car_id, date_debut, date_fin, prix_total) VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([$userId, $carId, $dateDebut, $dateFin, $prixTotal]);
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT cr.*, c.marque, c.modele, c.type, c.carburant
             FROM car_rentals cr
             JOIN cars c ON cr.car_id = c.id
             WHERE cr.user_id = ?
             ORDER BY cr.created_at DESC'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCarId(int $carId): array
    {
        // Aligné avec CarModel::isAvailableForPeriod : bloquer tant que la location n'est pas annulée
        $stmt = $this->pdo->prepare(
            'SELECT * FROM car_rentals
             WHERE car_id = ? AND statut <> ?
             ORDER BY date_debut ASC'
        );
        $stmt->execute([$carId, 'annulee']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllWithUser(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT cr.*, c.marque, c.modele, c.type, c.carburant, u.nom, u.prenom, u.email
             FROM car_rentals cr
             JOIN cars c ON cr.car_id = c.id
             JOIN users u ON cr.user_id = u.id
             ORDER BY cr.created_at DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare('UPDATE car_rentals SET statut = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }

    /**
     * Annulation par le client : uniquement ses locations, statuts en_attente ou confirmée (futur).
     */
    public function cancelByUser(int $rentalId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE car_rentals SET statut = ?
             WHERE id = ? AND user_id = ?
             AND (
                 statut = \'en_attente\'
                 OR (statut = \'confirmee\' AND date_debut > CURDATE())
             )'
        );
        $stmt->execute(['annulee', $rentalId, $userId]);

        return $stmt->rowCount() > 0;
    }
}