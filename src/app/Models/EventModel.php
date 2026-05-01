<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class EventModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->ensureTableExists();
    }

    public function create(
        string $titre,
        int $hotelId,
        string $chanteur,
        string $dateDebut,
        string $dateFin,
        string $description,
        ?string $imageUrl
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO events (titre, hotel_id, chanteur, date_debut, date_fin, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$titre, $hotelId, $chanteur, $dateDebut, $dateFin, $description, $imageUrl]);
    }

    public function findAll(): array
    {
        return $this->pdo->query(
            'SELECT e.*, COALESCE(h.nom, \'\') AS hotel
             FROM events e
             LEFT JOIN hotels h ON h.id = e.hotel_id
             ORDER BY e.date_debut DESC, e.id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findUpcoming(int $limit = 12): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.*, COALESCE(h.nom, \'\') AS hotel
             FROM events e
             LEFT JOIN hotels h ON h.id = e.hotel_id
             WHERE e.date_fin >= CURDATE()
             ORDER BY e.date_debut ASC, e.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByHotelId(int $hotelId, int $limit = 6): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.*, COALESCE(h.nom, \'\') AS hotel
             FROM events e
             LEFT JOIN hotels h ON h.id = e.hotel_id
             WHERE e.hotel_id = :hotel_id
             ORDER BY e.date_debut ASC, e.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':hotel_id', $hotelId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.*, COALESCE(h.nom, \'\') AS hotel
             FROM events e
             LEFT JOIN hotels h ON h.id = e.hotel_id
             WHERE e.id = ?
             LIMIT 1'
        );
        $stmt->execute([$id]);

        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        return $event === false ? null : $event;
    }

    public function update(
        int $id,
        string $titre,
        int $hotelId,
        string $chanteur,
        string $dateDebut,
        string $dateFin,
        string $description,
        ?string $imageUrl
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE events
             SET titre = ?, hotel_id = ?, chanteur = ?, date_debut = ?, date_fin = ?, description = ?, image_url = ?
             WHERE id = ?'
        );
        $stmt->execute([$titre, $hotelId, $chanteur, $dateDebut, $dateFin, $description, $imageUrl, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM events WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function findPublicFeed(int $limit = 6): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.id, e.titre, COALESCE(h.nom, \'\') AS hotel, e.chanteur, e.date_debut, e.date_fin, e.description, e.image_url
             FROM events e
             LEFT JOIN hotels h ON h.id = e.hotel_id
             WHERE e.date_fin >= CURDATE()
             ORDER BY e.date_debut ASC, e.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensureTableExists(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS hotels (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(150) NOT NULL,
                ville VARCHAR(100) NOT NULL,
                adresse VARCHAR(255) NOT NULL,
                description TEXT,
                image_url VARCHAR(255),
                etoiles INT DEFAULT 3,
                prix_nuit DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
        );

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titre VARCHAR(180) NOT NULL,
                hotel_id INT NOT NULL,
                chanteur VARCHAR(120) NOT NULL,
                date_debut DATE NOT NULL,
                date_fin DATE NOT NULL,
                description TEXT NOT NULL,
                image_url VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_events_hotel_id (hotel_id),
                INDEX idx_events_date_debut (date_debut),
                INDEX idx_events_date_fin (date_fin),
                CONSTRAINT fk_events_hotel_id
                    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
        );
    }
}
