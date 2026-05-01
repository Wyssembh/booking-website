<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EventModel;

final class EventController extends Controller
{
    private EventModel $events;

    public function __construct()
    {
        $this->events = new EventModel();
    }

    public function feed(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'events' => $this->events->findPublicFeed(6),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
