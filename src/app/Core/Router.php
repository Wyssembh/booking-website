<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $route, callable $handler): void
    {
        $key = strtoupper($method) . ':' . trim($route, '/');
        $this->routes[$key] = $handler;
    }

    public function dispatch(string $method, string $route): void
    {
        $key = strtoupper($method) . ':' . trim($route, '/');

        if (!isset($this->routes[$key])) {
            http_response_code(404);
            echo 'Page non trouvee';
            return;
        }

        ($this->routes[$key])();
    }
}
