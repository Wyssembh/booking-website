<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CarModel;

final class CarController extends Controller
{
    private CarModel $cars;

    public function __construct()
    {
        $this->cars = new CarModel();
    }

    private function handleImageUpload(): ?string
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = __DIR__ . '/../../uploads/cars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('car_', true) . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/cars/' . $filename;
        }

        return null;
    }

    public function index(): void
    {
        $this->requireAdmin();

        $cars = $this->cars->findAll();

        $this->view('cars/index', [
            'cars' => $cars,
        ]);
    }

    public function add(): void
    {
        $this->requireAdmin();

        $success = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $marque = trim((string) ($_POST['marque'] ?? ''));
            $modele = trim((string) ($_POST['modele'] ?? ''));
            $type = trim((string) ($_POST['type'] ?? 'economique'));
            $portes = max(2, (int) ($_POST['portes'] ?? 4));
            $carburant = trim((string) ($_POST['carburant'] ?? 'essence'));
            $prixParJour = max(0.01, (float) ($_POST['prix_par_jour'] ?? 0));
            $image = $this->handleImageUpload();

            if ($marque === '' || $modele === '') {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } else {
                $this->cars->create($marque, $modele, $type, $portes, $carburant, $prixParJour, $image);
                $success = 'Voiture ajoutee avec succes !';
            }
        }

        $this->view('cars/add', [
            'success' => $success,
            'error' => $error,
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $success = '';
        $error = '';
        $carId = max(0, (int) ($_GET['id'] ?? $_POST['id'] ?? 0));
        $car = $this->cars->findById($carId);

        if ($car === null) {
            $this->redirect('cars');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $marque = trim((string) ($_POST['marque'] ?? ''));
            $modele = trim((string) ($_POST['modele'] ?? ''));
            $type = trim((string) ($_POST['type'] ?? 'economique'));
            $portes = max(2, (int) ($_POST['portes'] ?? 4));
            $carburant = trim((string) ($_POST['carburant'] ?? 'essence'));
            $prixParJour = max(0.01, (float) ($_POST['prix_par_jour'] ?? 0));
            $statut = trim((string) ($_POST['statut'] ?? 'disponible'));
            $image = $this->handleImageUpload();

            // Si pas de nouvelle image uploadée, garder l'ancienne
            if ($image === null && isset($car['image'])) {
                $image = $car['image'];
            }

            if ($marque === '' || $modele === '') {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } else {
                $this->cars->update($carId, $marque, $modele, $type, $portes, $carburant, $prixParJour, $statut, $image);
                $success = 'Voiture modifiee avec succes !';
                $car = $this->cars->findById($carId);
            }
        }

        $this->view('cars/edit', [
            'success' => $success,
            'error' => $error,
            'car' => $car,
        ]);
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $carId = max(0, (int) ($_POST['id'] ?? 0));
        $car = $this->cars->findById($carId);

        if ($car !== null) {
            $this->cars->delete($carId);
        }

        $this->redirect('cars');
    }
}