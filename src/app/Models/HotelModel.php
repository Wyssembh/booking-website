<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class HotelModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getFeatured(int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nom, ville, adresse, description, image_url, etoiles, prix_nuit
             FROM hotels
             ORDER BY etoiles DESC, prix_nuit DESC, id ASC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->addSlugs($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create(
        string $nom,
        string $ville,
        string $adresse,
        ?string $description,
        ?string $imageUrl,
        int $etoiles,
        ?float $prixNuit
    ): void {
        $stmt = $this->pdo->prepare(
            'INSERT INTO hotels (nom, ville, adresse, description, image_url, etoiles, prix_nuit) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$nom, $ville, $adresse, $description, $imageUrl, $etoiles, $prixNuit]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM hotels ORDER BY created_at DESC');
        $stmt->execute();

        return $this->addSlugs($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM hotels WHERE id = ?');
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        $result['slug'] = slugify((string) ($result['nom'] ?? ''));
        return $result;
    }

    public function update(
        int $id,
        string $nom,
        string $ville,
        string $adresse,
        ?string $description,
        ?string $imageUrl,
        int $etoiles,
        ?float $prixNuit
    ): void {
        $stmt = $this->pdo->prepare(
            'UPDATE hotels SET nom = ?, ville = ?, adresse = ?, description = ?, image_url = ?, etoiles = ?, prix_nuit = ? WHERE id = ?'
        );
        $stmt->execute([$nom, $ville, $adresse, $description, $imageUrl, $etoiles, $prixNuit, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM hotels WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function getAll(?string $search, ?string $city, ?int $limit, ?int $offset): array
    {
        $params = [];
        $sql = 'SELECT id, nom, ville, adresse, description, image_url, etoiles, prix_nuit FROM hotels';
        $sql .= $this->buildFilters($search, $city, $params);
        $sql .= ' ORDER BY nom ASC';

        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
            if ($offset !== null) {
                $sql .= ' OFFSET :offset';
            }
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
        }
        $stmt->execute();

        return $this->addSlugs($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countAll(?string $search, ?string $city): int
    {
        $params = [];
        $sql = 'SELECT COUNT(*) FROM hotels';
        $sql .= $this->buildFilters($search, $city, $params);

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getCities(): array
    {
        return $this->pdo->query('SELECT DISTINCT ville FROM hotels WHERE ville <> "" ORDER BY ville')
            ->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->query('SELECT id, nom, ville, adresse, description, image_url, etoiles, prix_nuit FROM hotels ORDER BY nom ASC');
        $hotels = $this->addSlugs($stmt->fetchAll(PDO::FETCH_ASSOC));

        foreach ($hotels as $hotel) {
            if (($hotel['slug'] ?? '') === $slug) {
                return $hotel;
            }
        }

        return null;
    }

    private function buildFilters(?string $search, ?string $city, array &$params): string
    {
        $clauses = [];

        if ($search !== null && $search !== '') {
            $params['search'] = '%' . $search . '%';
            $clauses[] = '(nom LIKE :search OR ville LIKE :search OR description LIKE :search)';
        }

        if ($city !== null && $city !== '') {
            $params['city'] = $city;
            $clauses[] = 'ville = :city';
        }

        return $clauses ? ' WHERE ' . implode(' AND ', $clauses) : '';
    }

    private function addSlugs(array $hotels): array
    {
        foreach ($hotels as &$hotel) {
            $hotel['slug'] = slugify((string) ($hotel['nom'] ?? ''));
        }
        unset($hotel);

        return $hotels;
    }
}
