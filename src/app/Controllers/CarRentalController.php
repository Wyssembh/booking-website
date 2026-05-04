<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CarModel;
use App\Models\CarRentalModel;
use App\Models\ReservationModel;

final class CarRentalController extends Controller
{
    private CarModel $cars;
    private CarRentalModel $rentals;
    private ReservationModel $reservations;

    public function __construct()
    {
        $this->cars = new CarModel();
        $this->rentals = new CarRentalModel();
        $this->reservations = new ReservationModel();
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $success = $_GET['success'] ?? '';
        $error = $_GET['error'] ?? '';

        // Récupérer les voitures disponibles
        $availableCars = $this->cars->getAvailableCars();

        // Récupérer les locations existantes de l'utilisateur
        $userRentals = $this->rentals->findByUserId($userId);

        $this->view('cars/rent', [
            'success' => $success,
            'error' => $error,
            'available_cars' => $availableCars,
            'user_rentals' => $userRentals,
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $success = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $carId = (int) ($_POST['car_id'] ?? 0);
            $dateDebut = trim((string) ($_POST['date_debut'] ?? ''));
            $dateFin = trim((string) ($_POST['date_fin'] ?? ''));

            if ($carId === 0 || $dateDebut === '' || $dateFin === '') {
                $error = 'Veuillez remplir tous les champs.';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateDebut) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFin)) {
                $error = 'Format de date invalide.';
            } elseif (strtotime($dateDebut) === false || strtotime($dateFin) === false) {
                $error = 'Dates invalides.';
            } elseif (strtotime($dateDebut) > strtotime($dateFin)) {
                $error = 'La date de fin ne peut pas être avant la date de début.';
            } elseif (strtotime($dateDebut) < strtotime('today')) {
                $error = 'La date de début ne peut pas être dans le passé.';
            } else {
                $car = $this->cars->findById($carId);
                if ($car === null) {
                    $error = 'Voiture introuvable.';
                } elseif (($car['statut'] ?? '') !== 'disponible') {
                    $error = 'Cette voiture n\'est plus disponible à la location.';
                } elseif (!$this->cars->isAvailableForPeriod($carId, $dateDebut, $dateFin)) {
                    $error = 'Cette voiture n\'est pas disponible pour les dates sélectionnées.';
                } else {
                    // Nombre de jours facturés : même jour début/fin = 1 jour (aligné avec le calendrier)
                    $days = max(1, (int) round((strtotime($dateFin) - strtotime($dateDebut)) / 86400));
                    $prixTotal = round($days * (float) $car['prix_par_jour'], 2);

                    try {
                        $this->rentals->create($userId, $carId, $dateDebut, $dateFin, $prixTotal);
                        $success = 'Votre demande de location a été enregistrée avec succès !';
                    } catch (\Throwable) {
                        $error = 'Impossible d\'enregistrer la location. Réessayez ou contactez l\'administrateur.';
                    }
                }
            }
        }

        // Rediriger vers la page de location avec le message
        $redirectUrl = app_url('cars/rent');
        if (!empty($success)) {
            $redirectUrl .= '&success=' . urlencode($success);
        } elseif (!empty($error)) {
            $redirectUrl .= '&error=' . urlencode($error);
        }
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function getBookedDates(): void
    {
        $this->requireLogin();

        $carId = (int) ($_GET['car_id'] ?? 0);

        if ($carId === 0) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de voiture manquant']);
            return;
        }

        // Récupérer toutes les locations pour cette voiture
        $bookedPeriods = $this->rentals->findByCarId($carId);

        $bookedDates = [];

        foreach ($bookedPeriods as $period) {
            $startDate = strtotime($period['date_debut']);
            $endDate = strtotime($period['date_fin']);

            // Ajouter chaque date de la période
            $currentDate = $startDate;
            while ($currentDate <= $endDate) {
                $bookedDates[] = date('Y-m-d', $currentDate);
                $currentDate = strtotime('+1 day', $currentDate);
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'booked_dates' => array_values(array_unique($bookedDates, SORT_STRING)),
        ]);
    }

    public function cancel(): void
    {
        $this->requireLogin();

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $rentalId = (int) ($_POST['rental_id'] ?? 0);
        $success = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rentalId > 0) {
            if ($this->rentals->cancelByUser($rentalId, $userId)) {
                $success = 'La location a été annulée.';
            } else {
                $error = 'Annulation impossible (location introuvable, déjà terminée ou non annulable).';
            }
        } else {
            $error = 'Requête invalide.';
        }

        $redirectUrl = app_url('cars/rent');
        if ($success !== '') {
            $redirectUrl .= '&success=' . urlencode($success);
        } else {
            $redirectUrl .= '&error=' . urlencode($error);
        }
        header('Location: ' . $redirectUrl);
        exit;
    }
}