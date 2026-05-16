<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ReservationModel;

final class ReservationController extends Controller
{
    private ReservationModel $reservations;

    public function __construct()
    {
        $this->reservations = new ReservationModel();
    }

    public function index(): void
    {
        $this->requireLogin();

        $success = '';
        $error = '';
        $tarifs = $this->reservations->getTarifs();
        $selectedHotel = trim((string) ($_GET['hotel'] ?? ''));
        if ($selectedHotel !== '') {
            $matchedHotel = '';
            if (isset($tarifs[$selectedHotel])) {
                $matchedHotel = $selectedHotel;
            } else {
                foreach (array_keys($tarifs) as $hotelName) {
                    if (strcasecmp($hotelName, $selectedHotel) === 0) {
                        $matchedHotel = $hotelName;
                        break;
                    }
                }
            }
            $selectedHotel = $matchedHotel;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hotel = trim(strip_tags((string) ($_POST['hotel'] ?? '')));
            $chambre = trim(strip_tags((string) ($_POST['chambre'] ?? '')));
            $arrivee = trim((string) ($_POST['date_arrivee'] ?? ''));
            $depart = trim((string) ($_POST['date_depart'] ?? ''));
            $nbPers = max(1, (int) ($_POST['nb_personnes'] ?? 1));
            $userId = (int) $_SESSION['user_id'];

            if ($this->reservations->hasPendingReservation($userId)) {
                $error = 'Vous avez deja une reservation en attente. Attendez la confirmation avant de creer une nouvelle reservation.';
            } elseif ($hotel === '' || $chambre === '' || $arrivee === '' || $depart === '') {
                $error = 'Veuillez remplir tous les champs.';
            } elseif (!isset($tarifs[$hotel])) {
                $error = 'Veuillez choisir un hotel valide.';
            } elseif (!isset($tarifs[$hotel][$chambre])) {
                $error = 'Veuillez choisir un type de chambre valide.';
            } elseif ($depart <= $arrivee) {
                $error = "La date de depart doit etre apres la date d'arrivee.";
            } else {
                $hotelId = $this->reservations->getHotelIdByName($hotel);
                if ($hotelId === null) {
                    $error = 'Hotel invalide. Merci de choisir un hotel de la liste.';
                } else {
                $calculation = $this->reservations->calculateTotal($hotel, $chambre, $arrivee, $depart);
                $this->reservations->create(
                    $userId,
                    $hotelId,
                    $chambre,
                    $arrivee,
                    $depart,
                    $nbPers,
                    (float) $calculation['prix_total']
                );

                $success = sprintf(
                    'Reservation en attente enregistree ! Total : <strong>%d EUR</strong> pour %d nuit(s).',
                    (int) $calculation['prix_total'],
                    (int) $calculation['nuits']
                );
                }
            }
        }

        $userId = (int) $_SESSION['user_id'];

        $this->view('reservation/index', [
            'success' => $success,
            'error' => $error,
            'tarifs' => $tarifs,
            'selected_hotel' => $selectedHotel,
            'mes_reservations' => $this->reservations->findByUserId($userId),
            'has_pending_reservation' => $this->reservations->hasPendingReservation($userId),
            'has_confirmed_reservation' => $this->reservations->hasConfirmedReservation($userId),
        ]);
    }
}
