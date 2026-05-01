<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\HotelModel;
use PDOException;

final class HotelAdminController extends Controller
{
    private HotelModel $hotels;

    public function __construct()
    {
        $this->hotels = new HotelModel();
    }

    public function index(): void
    {
        $this->requireAdmin();

        $success = (string) ($_SESSION['flash_hotel_success'] ?? '');
        $error = (string) ($_SESSION['flash_hotel_error'] ?? '');
        unset($_SESSION['flash_hotel_success'], $_SESSION['flash_hotel_error']);

        $this->view('hotels/index', [
            'hotels' => $this->hotels->findAll(),
            'success' => $success,
            'error' => $error,
        ]);
    }

    public function add(): void
    {
        $this->requireAdmin();

        $success = '';
        $error = '';
        $old = $this->getPayloadDefaults();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->getPayloadFromPost();
            $validation = $this->validatePayload($old);

            if ($validation !== '') {
                $error = $validation;
            } else {
                $this->hotels->create(
                    $old['nom'],
                    $old['ville'],
                    $old['adresse'],
                    $old['description'] !== '' ? $old['description'] : null,
                    $old['image_url'] !== '' ? $old['image_url'] : null,
                    (int) $old['etoiles'],
                    $old['prix_nuit'] !== '' ? (float) $old['prix_nuit'] : null
                );
                $success = 'Hotel ajoute avec succes.';
                $old = $this->getPayloadDefaults();
            }
        }

        $this->view('hotels/add', [
            'success' => $success,
            'error' => $error,
            'old' => $old,
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $success = '';
        $error = '';
        $hotelId = max(0, (int) ($_GET['id'] ?? $_POST['id'] ?? 0));
        $hotel = $this->hotels->findById($hotelId);

        if ($hotel === null) {
            $this->redirect('admin/hotels');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = $this->getPayloadFromPost();
            $validation = $this->validatePayload($payload);

            if ($validation !== '') {
                $error = $validation;
            } else {
                $this->hotels->update(
                    $hotelId,
                    $payload['nom'],
                    $payload['ville'],
                    $payload['adresse'],
                    $payload['description'] !== '' ? $payload['description'] : null,
                    $payload['image_url'] !== '' ? $payload['image_url'] : null,
                    (int) $payload['etoiles'],
                    $payload['prix_nuit'] !== '' ? (float) $payload['prix_nuit'] : null
                );
                $success = 'Hotel modifie avec succes.';
                $hotel = $this->hotels->findById($hotelId);
            }
        }

        $this->view('hotels/edit', [
            'success' => $success,
            'error' => $error,
            'hotel' => $hotel,
        ]);
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $hotelId = max(0, (int) ($_POST['id'] ?? 0));
        if ($hotelId <= 0) {
            $this->redirect('admin/hotels');
            return;
        }

        try {
            $this->hotels->delete($hotelId);
            $_SESSION['flash_hotel_success'] = 'Hotel supprime avec succes.';
        } catch (PDOException $e) {
            $_SESSION['flash_hotel_error'] = 'Suppression impossible : hotel lie a des reservations ou evenements.';
        }

        $this->redirect('admin/hotels');
    }

    private function getPayloadDefaults(): array
    {
        return [
            'nom' => '',
            'ville' => '',
            'adresse' => '',
            'description' => '',
            'image_url' => '',
            'etoiles' => '3',
            'prix_nuit' => '',
        ];
    }

    private function getPayloadFromPost(): array
    {
        return [
            'nom' => trim(strip_tags((string) ($_POST['nom'] ?? ''))),
            'ville' => trim(strip_tags((string) ($_POST['ville'] ?? ''))),
            'adresse' => trim(strip_tags((string) ($_POST['adresse'] ?? ''))),
            'description' => trim(strip_tags((string) ($_POST['description'] ?? ''))),
            'image_url' => trim(strip_tags((string) ($_POST['image_url'] ?? ''))),
            'etoiles' => (string) max(1, min(5, (int) ($_POST['etoiles'] ?? 3))),
            'prix_nuit' => trim((string) ($_POST['prix_nuit'] ?? '')),
        ];
    }

    private function validatePayload(array $payload): string
    {
        if ($payload['nom'] === '' || $payload['ville'] === '' || $payload['adresse'] === '') {
            return 'Veuillez remplir les champs obligatoires.';
        }

        if ($payload['image_url'] !== '' && filter_var($payload['image_url'], FILTER_VALIDATE_URL) === false) {
            return 'URL d image invalide.';
        }

        if ($payload['prix_nuit'] !== '' && (float) $payload['prix_nuit'] <= 0) {
            return 'Le prix par nuit doit etre superieur a 0.';
        }

        return '';
    }
}
