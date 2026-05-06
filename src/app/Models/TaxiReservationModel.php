<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class TaxiReservationModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getTaxiTypes(): array
    {
        return [
            'standard' => 35,
            'vito' => 50,
            'van' => 70,
        ];
    }

    public function calculateTotal(string $type, int $passengers): float
    {
        $base = $this->getTaxiTypes()[$type] ?? 35;
        $extra = max(0, $passengers - 1) * 10;

        return (float) ($base + $extra);
    }

    public function create(
        int $userId,
        int $reservationId,
        string $pickup,
        string $dropoff,
        string $dateTime,
        string $type,
        int $passengers,
        float $prixTotal
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO taxi_reservations (user_id, reservation_id, adresse_depart, adresse_arrivee, date_heure, type, nb_passagers, prix_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([$userId, $reservationId, $pickup, $dropoff, $dateTime, $type, $passengers, $prixTotal]);
    }

    public function getByIdAndUser(int $id, int $userId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT tr.*, r.hotel AS reservation_hotel, r.date_arrivee, r.date_depart FROM taxi_reservations tr JOIN reservations r ON tr.reservation_id = r.id WHERE tr.id = ? AND tr.user_id = ?');
        $stmt->execute([$id, $userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(
        int $id,
        string $pickup,
        string $dropoff,
        string $dateTime,
        string $type,
        int $passengers,
        float $prixTotal
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE taxi_reservations SET adresse_depart = ?, adresse_arrivee = ?, date_heure = ?, type = ?, nb_passagers = ?, prix_total = ? WHERE id = ?'
        );

        $stmt->execute([$pickup, $dropoff, $dateTime, $type, $passengers, $prixTotal, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM taxi_reservations WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT tr.*, r.id AS reservation_id, r.hotel AS reservation_hotel FROM taxi_reservations tr JOIN reservations r ON tr.reservation_id = r.id WHERE tr.user_id = ? ORDER BY tr.created_at DESC');
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existsForReservation(int $reservationId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM taxi_reservations WHERE reservation_id = ?');
        $stmt->execute([$reservationId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function findAvailableReservationsByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*
             FROM reservations r
             LEFT JOIN taxi_reservations tr ON tr.reservation_id = r.id
             WHERE r.user_id = ? AND r.statut = ? AND tr.id IS NULL
             ORDER BY r.date_arrivee DESC'
        );
        $stmt->execute([$userId, 'confirmee']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findUpcomingReservations(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT tr.*, u.nom, u.prenom, u.email, r.hotel AS reservation_hotel, r.id AS reservation_id
            FROM taxi_reservations tr
            JOIN users u ON tr.user_id = u.id
            JOIN reservations r ON tr.reservation_id = r.id
            WHERE tr.date_heure >= NOW()
            ORDER BY tr.date_heure ASC
        ');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
