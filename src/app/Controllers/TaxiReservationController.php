<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ReservationModel;
use App\Models\TaxiReservationModel;

final class TaxiReservationController extends Controller
{
    private TaxiReservationModel $taxiReservations;
    private ReservationModel $reservations;

    public function __construct()
    {
        $this->taxiReservations = new TaxiReservationModel();
        $this->reservations = new ReservationModel();
    }

    public function index(): void
    {
        $this->requireLogin();

        $success = '';
        $error = '';

        $userId = (int) $_SESSION['user_id'];
        $canBookTaxi = $this->reservations->hasConfirmedReservation($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$canBookTaxi) {
                $error = 'Vous devez avoir une reservation d\'hotel confirmee pour reserver un taxi.';
            } else {
                $reservationId = max(0, (int) ($_POST['reservation_id'] ?? 0));
                $reservation = $this->reservations->getConfirmedReservationById($userId, $reservationId);
                $adresseDepart = trim((string) ($_POST['adresse_depart'] ?? ''));
                $adresseArrivee = trim((string) ($_POST['adresse_arrivee'] ?? ''));
                $dateArrivee = trim((string) ($_POST['date_arrivee'] ?? ''));
                $heureArrivee = trim((string) ($_POST['heure_arrivee'] ?? ''));
                $type = trim((string) ($_POST['type'] ?? 'standard'));
                $nbPassagers = max(1, (int) ($_POST['nb_passagers'] ?? 1));

                $dateHeure = $dateArrivee . ' ' . $heureArrivee . ':00';

                if ($reservation === null) {
                    $error = 'Reservation d\'hotel invalide.';
                } elseif ($this->taxiReservations->existsForReservation($reservationId)) {
                    $error = 'Un taxi est deja reserve pour cette reservation d\'hotel.';
                } elseif ($adresseDepart === '' || $adresseArrivee === '' || $dateArrivee === '' || $heureArrivee === '') {
                    $error = 'Veuillez remplir tous les champs.';
                } elseif (strtotime($dateHeure) <= time()) {
                    $error = 'La date et l\'heure doivent etre dans le futur.';
                } else {
                    $prixTotal = $this->taxiReservations->calculateTotal($type, $nbPassagers);
                    $this->taxiReservations->create(
                        $userId,
                        $reservationId,
                        $adresseDepart,
                        $adresseArrivee,
                        $dateHeure,
                        $type,
                        $nbPassagers,
                        $prixTotal
                    );

                    $success = sprintf(
                        'Reservation de taxi enregistree ! Total: <strong>%.2f EUR</strong>.',
                        $prixTotal
                    );
                }
            }
        }

        $userId = (int) $_SESSION['user_id'];
        $canBookTaxi = $this->reservations->hasConfirmedReservation($userId);
        $lastConfirmedReservation = $this->reservations->getLastConfirmedReservation($userId);
        $confirmedReservations = $this->reservations->findConfirmedByUserId($userId);

        $this->view('taxi/index', [
            'success' => $success,
            'error' => $error,
            'taxi_types' => $this->taxiReservations->getTaxiTypes(),
            'mes_reservations' => $this->taxiReservations->findByUserId($userId),
            'can_book_taxi' => $canBookTaxi,
            'last_confirmed_reservation' => $lastConfirmedReservation,
            'confirmed_reservations' => $confirmedReservations,
            'available_reservations' => $this->taxiReservations->findAvailableReservationsByUserId($userId),
        ]);
    }

    public function edit(): void
    {
        $this->requireLogin();

        $success = '';
        $error = '';
        $userId = (int) $_SESSION['user_id'];
        $taxiId = max(0, (int) ($_GET['id'] ?? $_POST['id'] ?? 0));
        $taxiReservation = $this->taxiReservations->getByIdAndUser($taxiId, $userId);

        if ($taxiReservation === null) {
            $this->redirect('taxi');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adresseDepart = trim((string) ($_POST['adresse_depart'] ?? ''));
            $adresseArrivee = trim((string) ($_POST['adresse_arrivee'] ?? ''));
            $dateArrivee = trim((string) ($_POST['date_arrivee'] ?? ''));
            $heureArrivee = trim((string) ($_POST['heure_arrivee'] ?? ''));
            $type = trim((string) ($_POST['type'] ?? $taxiReservation['type']));
            $nbPassagers = max(1, (int) ($_POST['nb_passagers'] ?? (int) $taxiReservation['nb_passagers']));

            $dateHeure = $dateArrivee . ' ' . $heureArrivee . ':00';

            if ($adresseDepart === '' || $adresseArrivee === '' || $dateArrivee === '' || $heureArrivee === '') {
                $error = 'Veuillez remplir tous les champs.';
            } elseif (strtotime($dateHeure) <= time()) {
                $error = 'La date et l\'heure doivent etre dans le futur.';
            } else {
                $prixTotal = $this->taxiReservations->calculateTotal($type, $nbPassagers);
                $this->taxiReservations->update(
                    $taxiId,
                    $adresseDepart,
                    $adresseArrivee,
                    $dateHeure,
                    $type,
                    $nbPassagers,
                    $prixTotal
                );

                $success = sprintf('Reservation de taxi modifiee ! Total: <strong>%.2f EUR</strong>.', $prixTotal);
                $taxiReservation = $this->taxiReservations->getByIdAndUser($taxiId, $userId);
            }
        }

        $this->view('taxi/edit', [
            'success' => $success,
            'error' => $error,
            'taxi_types' => $this->taxiReservations->getTaxiTypes(),
            'taxi_reservation' => $taxiReservation,
        ]);
    }

    public function delete(): void
    {
        $this->requireLogin();

        $userId = (int) $_SESSION['user_id'];
        $taxiId = max(0, (int) ($_POST['id'] ?? 0));
        $taxiReservation = $this->taxiReservations->getByIdAndUser($taxiId, $userId);

        if ($taxiReservation !== null) {
            $this->taxiReservations->delete($taxiId);
        }

        $this->redirect('taxi');
    }
}
