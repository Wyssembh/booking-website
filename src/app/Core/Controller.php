<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require dirname(__DIR__) . '/Views/' . $viewPath . '.php';
    }

    protected function redirect(string $route): void
    {
        redirect_to($route);
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin(): bool
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }

    protected function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            $this->redirect('login');
        }
    }
}
