<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EventModel;
use App\Models\ReservationModel;

final class EventAdminController extends Controller
{
    private EventModel $events;
    private ReservationModel $reservations;

    public function __construct()
    {
        $this->events = new EventModel();
        $this->reservations = new ReservationModel();
    }

    public function index(): void
    {
        $this->requireAdmin();

        $success = (string) ($_SESSION['flash_event_success'] ?? '');
        $error = (string) ($_SESSION['flash_event_error'] ?? '');
        unset($_SESSION['flash_event_success'], $_SESSION['flash_event_error']);

        $old = [
            'titre' => '',
            'hotel' => '',
            'chanteur' => '',
            'date_debut' => '',
            'date_fin' => '',
            'description' => '',
            'image_url' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = (string) ($_POST['event_action'] ?? '');

            if ($action === 'delete') {
                $id = (int) ($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->events->delete($id);
                    $_SESSION['flash_event_success'] = 'Evenement supprime avec succes.';
                }

                $this->redirect('events');
            }

            if ($action === 'create' || $action === 'update') {
                $old = $this->getEventPayloadFromPost();
                $validation = $this->validatePayload($old);

                if ($validation !== '') {
                    $error = $validation;
                } else {
                    $hotelId = $this->reservations->getHotelIdByName($old['hotel']);
                    if ($hotelId === null) {
                        $error = 'Veuillez choisir un hotel valide dans la liste.';
                    } else {
                    if ($action === 'create') {
                        $this->events->create(
                            $old['titre'],
                            $hotelId,
                            $old['chanteur'],
                            $old['date_debut'],
                            $old['date_fin'],
                            $old['description'],
                            $old['image_url'] !== '' ? $old['image_url'] : null
                        );
                        $_SESSION['flash_event_success'] = 'Evenement ajoute avec succes.';
                        $this->redirect('events');
                    }

                    $id = (int) ($_POST['id'] ?? 0);
                    if ($id <= 0 || $this->events->findById($id) === null) {
                        $error = 'Evenement introuvable pour la modification.';
                    } else {
                        $this->events->update(
                            $id,
                            $old['titre'],
                            $hotelId,
                            $old['chanteur'],
                            $old['date_debut'],
                            $old['date_fin'],
                            $old['description'],
                            $old['image_url'] !== '' ? $old['image_url'] : null
                        );
                        $_SESSION['flash_event_success'] = 'Evenement modifie avec succes.';
                        $this->redirect('events');
                    }
                    }
                }
            }
        }

        $editId = (int) ($_GET['edit'] ?? 0);
        $editingEvent = $editId > 0 ? $this->events->findById($editId) : null;
        $hotels = $this->reservations->getHotelNames();

        $this->view('event/index', [
            'success' => $success,
            'error' => $error,
            'events' => $this->events->findAll(),
            'editing_event' => $editingEvent,
            'old' => $old,
            'hotels' => $hotels,
        ]);
    }

    private function getEventPayloadFromPost(): array
    {
        return [
            'titre' => trim(strip_tags((string) ($_POST['titre'] ?? ''))),
            'hotel' => trim(strip_tags((string) ($_POST['hotel'] ?? ''))),
            'chanteur' => trim(strip_tags((string) ($_POST['chanteur'] ?? ''))),
            'date_debut' => (string) ($_POST['date_debut'] ?? ''),
            'date_fin' => (string) ($_POST['date_fin'] ?? ''),
            'description' => trim(strip_tags((string) ($_POST['description'] ?? ''))),
            'image_url' => trim(strip_tags((string) ($_POST['image_url'] ?? ''))),
        ];
    }

    private function validatePayload(array $payload): string
    {
        $allowedHotels = $this->reservations->getHotelNames();

        if (
            $payload['titre'] === '' ||
            $payload['hotel'] === '' ||
            $payload['chanteur'] === '' ||
            $payload['date_debut'] === '' ||
            $payload['date_fin'] === '' ||
            $payload['description'] === ''
        ) {
            return 'Veuillez remplir tous les champs obligatoires.';
        }

        if ($payload['date_fin'] < $payload['date_debut']) {
            return 'La date de fin doit etre superieure ou egale a la date de debut.';
        }

        if (!in_array($payload['hotel'], $allowedHotels, true)) {
            return 'Veuillez choisir un hotel valide dans la liste.';
        }

        if ($payload['image_url'] !== '' && filter_var($payload['image_url'], FILTER_VALIDATE_URL) === false) {
            return 'L\'URL de l\'image est invalide.';
        }

        return '';
    }
}
