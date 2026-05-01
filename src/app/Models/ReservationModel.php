<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use DateTime;
use PDO;

final class ReservationModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getTarifs(): array
    {
        $roomMultipliers = [
            'Standard' => 1.0,
            'Superieure' => 1.3,
            'Suite' => 1.8,
        ];

        $stmt = $this->pdo->query('SELECT nom, prix_nuit FROM hotels ORDER BY nom');
        $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tarifs = [];

        foreach ($hotels as $hotel) {
            $name = (string) ($hotel['nom'] ?? '');
            $base = (float) ($hotel['prix_nuit'] ?? 0);
            if ($name === '') {
                continue;
            }

            foreach ($roomMultipliers as $room => $multiplier) {
                $tarifs[$name][$room] = round($base * $multiplier, 2);
            }
        }

        return $tarifs;
    }

    public function getHotelNames(): array
    {
        return $this->pdo->query('SELECT nom FROM hotels ORDER BY nom')->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public function getHotelIdByName(string $hotel): ?int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM hotels WHERE nom = ? LIMIT 1');
        $stmt->execute([$hotel]);

        $id = $stmt->fetchColumn();
        return $id === false ? null : (int) $id;
    }

    public function calculateTotal(string $hotel, string $chambre, string $arrivee, string $depart): array
    {
        $nuits = (new DateTime($arrivee))->diff(new DateTime($depart))->days;
        $tarifNuit = $this->getTarifs()[$hotel][$chambre] ?? 0;

        return [
            'nuits' => $nuits,
            'prix_total' => $tarifNuit * $nuits,
        ];
    }

    public function create(
        int $userId,
        int $hotelId,
        string $chambre,
        string $arrivee,
        string $depart,
        int $nbPersonnes,
        float $prixTotal
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reservations (user_id, hotel_id, chambre, date_arrivee, date_depart, nb_personnes, prix_total) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $hotelId, $chambre, $arrivee, $depart, $nbPersonnes, $prixTotal]);
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, COALESCE(h.nom, \'\') AS hotel
             FROM reservations r
             LEFT JOIN hotels h ON h.id = r.hotel_id
             WHERE r.user_id = ?
             ORDER BY r.created_at DESC'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hasPendingReservation(int $userId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM reservations WHERE user_id = ? AND statut = ?');
        $stmt->execute([$userId, 'en_attente']);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function hasConfirmedReservation(int $userId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM reservations WHERE user_id = ? AND statut = ?');
        $stmt->execute([$userId, 'confirmee']);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function getLastConfirmedReservation(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, COALESCE(h.nom, \'\') AS hotel
             FROM reservations r
             LEFT JOIN hotels h ON h.id = r.hotel_id
             WHERE r.user_id = ? AND r.statut = ?
             ORDER BY r.created_at DESC
             LIMIT 1'
        );
        $stmt->execute([$userId, 'confirmee']);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findConfirmedByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, COALESCE(h.nom, \'\') AS hotel
             FROM reservations r
             LEFT JOIN hotels h ON h.id = r.hotel_id
             WHERE r.user_id = ? AND r.statut = ?
             ORDER BY r.date_arrivee DESC'
        );
        $stmt->execute([$userId, 'confirmee']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConfirmedReservationById(int $userId, int $reservationId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.*, COALESCE(h.nom, \'\') AS hotel
             FROM reservations r
             LEFT JOIN hotels h ON h.id = r.hotel_id
             WHERE r.user_id = ? AND r.id = ? AND r.statut = ?'
        );
        $stmt->execute([$userId, $reservationId, 'confirmee']);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getDashboardStats(): array
    {
        return $this->pdo->query(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN statut = 'confirmee' THEN 1 ELSE 0 END) AS confirmees,
                SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) AS en_attente,
                SUM(CASE WHEN statut = 'annulee' THEN 1 ELSE 0 END) AS annulees,
                SUM(CASE WHEN statut = 'confirmee' THEN prix_total ELSE 0 END) AS revenus
             FROM reservations"
        )->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getReservationsByHotel(): array
    {
        return $this->pdo->query(
            'SELECT COALESCE(h.nom, \'Inconnu\') AS hotel, COUNT(*) AS nb, SUM(r.prix_total) AS total
             FROM reservations r
             LEFT JOIN hotels h ON h.id = r.hotel_id
             GROUP BY h.nom'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationsByMonth(): array
    {
        return $this->pdo->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS mois, COUNT(*) AS nb
             FROM reservations
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY mois
             ORDER BY mois"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare('UPDATE reservations SET statut = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }

    public function findAllWithUser(): array
    {
        return $this->pdo->query(
            'SELECT r.*, COALESCE(h.nom, \'\') AS hotel, u.nom, u.prenom, u.email
             FROM reservations r
             JOIN users u ON r.user_id = u.id
             LEFT JOIN hotels h ON h.id = r.hotel_id
             ORDER BY r.created_at DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
