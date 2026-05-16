<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EventModel;
use App\Models\HotelModel;

final class SiteController extends Controller
{
    private HotelModel $hotels;
    private EventModel $events;

    public function __construct()
    {
        $this->hotels = new HotelModel();
        $this->events = new EventModel();
    }

    public function home(): void
    {
        $navHotels = $this->hotels->getAll(null, null, null, null);
        $featured = $this->hotels->getFeatured(3);

        $this->view('site/index', [
            'nav_hotels' => $navHotels,
            'featured_hotels' => $this->padFeatured($featured),
        ]);
    }

    public function hotels(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $city = trim((string) ($_GET['ville'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;

        $total = $this->hotels->countAll($search, $city);
        $totalPages = max(1, (int) ceil($total / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;
        $hotels = $this->hotels->getAll($search, $city, $perPage, $offset);

        $this->view('site/hotels', [
            'nav_hotels' => $this->hotels->getAll(null, null, null, null),
            'hotels' => $hotels,
            'cities' => $this->hotels->getCities(),
            'search' => $search,
            'selected_city' => $city,
            'page' => $page,
            'total_pages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function hotelDetails(): void
    {
        $hotel = null;
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $hotel = $this->hotels->findById($id);
        }

        if ($hotel === null) {
            $slug = trim((string) ($_GET['slug'] ?? ''));
            if ($slug !== '') {
                $slug = slugify($slug);
                $hotel = $this->hotels->findBySlug($slug);
            }
        }

        if ($hotel === null) {
            http_response_code(404);
        }

        $events = $hotel !== null
            ? $this->events->findByHotelId((int) $hotel['id'], 6)
            : [];

        $this->view('site/hotel-details', [
            'nav_hotels' => $this->hotels->getAll(null, null, null, null),
            'hotel' => $hotel,
            'events' => $events,
        ]);
    }

    public function events(): void
    {
        $events = $this->events->findUpcoming(12);

        $this->view('site/events', [
            'nav_hotels' => $this->hotels->getAll(null, null, null, null),
            'events' => $events,
        ]);
    }

    private function padFeatured(array $hotels): array
    {
        if (count($hotels) >= 3) {
            return $hotels;
        }

        if ($hotels === []) {
            return $hotels;
        }

        while (count($hotels) < 3) {
            $hotels[] = $hotels[count($hotels) - 1];
        }

        return $hotels;
    }
}
