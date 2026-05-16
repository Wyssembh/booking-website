<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EventModel;
use App\Models\ReservationModel;
use App\Models\TaxiReservationModel;
use App\Models\CarRentalModel;

final class DashboardController extends Controller
{
    private ReservationModel $reservations;
    private EventModel $events;
    private TaxiReservationModel $taxiReservations;
    private CarRentalModel $carRentals;

    public function __construct()
    {
        $this->reservations = new ReservationModel();
        $this->events = new EventModel();
        $this->taxiReservations = new TaxiReservationModel();
        $this->carRentals = new CarRentalModel();
    }

    public function index(): void
    {
        $this->requireAdmin();

        $eventSuccess = (string) ($_SESSION['flash_event_success'] ?? '');
        $eventError = (string) ($_SESSION['flash_event_error'] ?? '');
        unset($_SESSION['flash_event_success'], $_SESSION['flash_event_error']);

        $eventOld = [
            'titre' => '',
            'hotel' => '',
            'chanteur' => '',
            'date_debut' => '',
            'date_fin' => '',
            'description' => '',
            'image_url' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['statut'], $_POST['id'])) {
            $allowedStatus = ['en_attente', 'confirmee', 'annulee'];
            $status = (string) $_POST['statut'];
            $id = (int) $_POST['id'];

            if (in_array($status, $allowedStatus, true) && $id > 0) {
                $this->reservations->updateStatus($id, $status);
            }

            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string) ($_POST['event_action'] ?? '') === 'create') {
            $eventOld = [
                'titre' => trim(strip_tags((string) ($_POST['titre'] ?? ''))),
                'hotel' => trim(strip_tags((string) ($_POST['hotel'] ?? ''))),
                'chanteur' => trim(strip_tags((string) ($_POST['chanteur'] ?? ''))),
                'date_debut' => (string) ($_POST['date_debut'] ?? ''),
                'date_fin' => (string) ($_POST['date_fin'] ?? ''),
                'description' => trim(strip_tags((string) ($_POST['description'] ?? ''))),
                'image_url' => trim(strip_tags((string) ($_POST['image_url'] ?? ''))),
            ];

            if (
                $eventOld['titre'] === '' ||
                $eventOld['hotel'] === '' ||
                $eventOld['chanteur'] === '' ||
                $eventOld['date_debut'] === '' ||
                $eventOld['date_fin'] === '' ||
                $eventOld['description'] === ''
            ) {
                $eventError = 'Veuillez remplir tous les champs obligatoires de l\'evenement.';
            } elseif ($eventOld['date_fin'] < $eventOld['date_debut']) {
                $eventError = 'La date de fin doit etre superieure ou egale a la date de debut.';
            } elseif ($eventOld['image_url'] !== '' && filter_var($eventOld['image_url'], FILTER_VALIDATE_URL) === false) {
                $eventError = 'L\'URL de l\'image est invalide.';
            } else {
                $hotelId = $this->reservations->getHotelIdByName($eventOld['hotel']);
                if ($hotelId === null) {
                    $eventError = 'Veuillez choisir un hotel valide dans la liste.';
                } else {
                $this->events->create(
                    $eventOld['titre'],
                    $hotelId,
                    $eventOld['chanteur'],
                    $eventOld['date_debut'],
                    $eventOld['date_fin'],
                    $eventOld['description'],
                    $eventOld['image_url'] !== '' ? $eventOld['image_url'] : null
                );

                $_SESSION['flash_event_success'] = 'Evenement ajoute avec succes.';
                $this->redirect('dashboard');
                }
            }
        }

        $parMois = $this->reservations->getReservationsByMonth();
        $parHotel = $this->reservations->getReservationsByHotel();

        $this->view('dashboard/index', [
            'stats' => $this->reservations->getDashboardStats(),
            'reservations' => $this->reservations->findAllWithUser(),
            'mois_labels' => json_encode(array_column($parMois, 'mois')),
            'mois_data' => json_encode(array_column($parMois, 'nb')),
            'hotel_labels' => json_encode(array_column($parHotel, 'hotel')),
            'hotel_data' => json_encode(array_column($parHotel, 'nb')),
            'events' => $this->events->findAll(),
            'event_success' => $eventSuccess,
            'event_error' => $eventError,
            'event_old' => $eventOld,
        ]);
    }

    public function taxis(): void
    {
        $this->requireAdmin();

        $this->view('dashboard/taxis', [
            'taxi_reservations' => $this->taxiReservations->findUpcomingReservations(),
        ]);
    }

    public function rentals(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['statut'], $_POST['id'])) {
            $allowedStatus = ['en_attente', 'confirmee', 'annulee', 'terminee'];
            $status = (string) $_POST['statut'];
            $id = (int) $_POST['id'];

            if (in_array($status, $allowedStatus, true) && $id > 0) {
                $this->carRentals->updateStatus($id, $status);
            }

            $this->redirect('dashboard/rentals');
        }

        $this->view('dashboard/rentals', [
            'car_rentals' => $this->carRentals->findAllWithUser(),
        ]);
    }
}
