<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\EventAdminController;
use App\Controllers\EventController;
use App\Controllers\HotelAdminController;
use App\Controllers\SiteController;
use App\Controllers\ReservationController;
use App\Controllers\TaxiReservationController;
use App\Controllers\CarController;
use App\Controllers\CarRentalController;
use App\Core\Router;

$router = new Router();

$authController = new AuthController();
$reservationController = new ReservationController();
$taxiController = new TaxiReservationController();
$dashboardController = new DashboardController();
$eventController = new EventController();
$siteController = new SiteController();
$hotelAdminController = new HotelAdminController();
$eventAdminController = new EventAdminController();
$carController = new CarController();
$carRentalController = new CarRentalController();

$router->add('GET', '', static function (): void {
    if (isset($_SESSION['user_id'])) {
        redirect_to(($_SESSION['role'] ?? 'client') === 'admin' ? 'dashboard' : 'reservation');
    }

    redirect_to('login');
});

$router->add('GET', 'login', static function () use ($authController): void {
    $authController->login();
});
$router->add('POST', 'login', static function () use ($authController): void {
    $authController->login();
});

$router->add('GET', 'register', static function () use ($authController): void {
    $authController->register();
});
$router->add('POST', 'register', static function () use ($authController): void {
    $authController->register();
});

$router->add('GET', 'logout', static function () use ($authController): void {
    $authController->logout();
});

$router->add('GET', 'profile', static function () use ($authController): void {
    $authController->profile();
});
$router->add('POST', 'profile', static function () use ($authController): void {
    $authController->profile();
});

$router->add('GET', 'reservation', static function () use ($reservationController): void {
    $reservationController->index();
});
$router->add('POST', 'reservation', static function () use ($reservationController): void {
    $reservationController->index();
});

$router->add('GET', 'taxi', static function () use ($taxiController): void {
    $taxiController->index();
});
$router->add('POST', 'taxi', static function () use ($taxiController): void {
    $taxiController->index();
});
$router->add('GET', 'taxi/edit', static function () use ($taxiController): void {
    $taxiController->edit();
});
$router->add('POST', 'taxi/edit', static function () use ($taxiController): void {
    $taxiController->edit();
});
$router->add('POST', 'taxi/delete', static function () use ($taxiController): void {
    $taxiController->delete();
});

$router->add('GET', 'dashboard', static function () use ($dashboardController): void {
    $dashboardController->index();
});
$router->add('POST', 'dashboard', static function () use ($dashboardController): void {
    $dashboardController->index();
});

$router->add('GET', 'events', static function () use ($eventAdminController): void {
    $eventAdminController->index();
});
$router->add('POST', 'events', static function () use ($eventAdminController): void {
    $eventAdminController->index();
});

$router->add('GET', 'events-feed', static function () use ($eventController): void {
    $eventController->feed();
});

$router->add('GET', 'admin/hotels', static function () use ($hotelAdminController): void {
    $hotelAdminController->index();
});
$router->add('GET', 'admin/hotels/add', static function () use ($hotelAdminController): void {
    $hotelAdminController->add();
});
$router->add('POST', 'admin/hotels/add', static function () use ($hotelAdminController): void {
    $hotelAdminController->add();
});
$router->add('GET', 'admin/hotels/edit', static function () use ($hotelAdminController): void {
    $hotelAdminController->edit();
});
$router->add('POST', 'admin/hotels/edit', static function () use ($hotelAdminController): void {
    $hotelAdminController->edit();
});
$router->add('POST', 'admin/hotels/delete', static function () use ($hotelAdminController): void {
    $hotelAdminController->delete();
});

$router->add('GET', 'home', static function () use ($siteController): void {
    $siteController->home();
});

$router->add('GET', 'hotels', static function () use ($siteController): void {
    $siteController->hotels();
});

$router->add('GET', 'hotel-details', static function () use ($siteController): void {
    $siteController->hotelDetails();
});

$router->add('GET', 'events-public', static function () use ($siteController): void {
    $siteController->events();
});

$router->add('GET', 'dashboard/taxis', static function () use ($dashboardController): void {
    $dashboardController->taxis();
});

$router->add('GET', 'dashboard/rentals', static function () use ($dashboardController): void {
    $dashboardController->rentals();
});

$router->add('POST', 'dashboard/rentals', static function () use ($dashboardController): void {
    $dashboardController->rentals();
});

$router->add('GET', 'cars', static function () use ($carController): void {
    $carController->index();
});
$router->add('POST', 'cars', static function () use ($carController): void {
    header('Location: ' . app_url('cars/add'));
    exit;
});
$router->add('GET', 'cars/add', static function () use ($carController): void {
    $carController->add();
});
$router->add('POST', 'cars/add', static function () use ($carController): void {
    $carController->add();
});
$router->add('GET', 'cars/edit', static function () use ($carController): void {
    $carController->edit();
});
$router->add('POST', 'cars/edit', static function () use ($carController): void {
    $carController->edit();
});
$router->add('POST', 'cars/delete', static function () use ($carController): void {
    $carController->delete();
});
$router->add('GET', 'cars/rent', static function () use ($carRentalController): void {
    $carRentalController->index();
});
$router->add('POST', 'cars/rent/create', static function () use ($carRentalController): void {
    $carRentalController->create();
});
$router->add('POST', 'cars/rent/cancel', static function () use ($carRentalController): void {
    $carRentalController->cancel();
});
$router->add('GET', 'cars/rent/api/booked-dates', static function () use ($carRentalController): void {
    $carRentalController->getBookedDates();
});

$route = trim((string) ($_GET['route'] ?? ''), '/');
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

$router->dispatch($method, $route);
