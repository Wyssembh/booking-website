<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

final class AuthController extends Controller
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect($this->isAdmin() ? 'dashboard' : 'reservation');
        }

        $error = '';
        $old = ['email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $old['email'] = $email;

            if ($email === '' || $password === '') {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                $user = $this->users->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['role'] = $user['role'];

                    $this->redirect($user['role'] === 'admin' ? 'dashboard' : 'reservation');
                }

                $error = 'Email ou mot de passe incorrect.';
            }
        }

        $this->view('auth/login', [
            'error' => $error,
            'old' => $old,
        ]);
    }

    public function register(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('reservation');
        }

        $error = '';
        $success = '';
        $old = ['nom' => '', 'prenom' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass = $_POST['password'] ?? '';
            $pass2 = $_POST['password2'] ?? '';

            $old = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email];

            if ($nom === '' || $prenom === '' || $email === '' || $pass === '') {
                $error = 'Veuillez remplir tous les champs.';
            } elseif ($pass !== $pass2) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif (strlen($pass) < 6) {
                $error = 'Le mot de passe doit contenir au moins 6 caracteres.';
            } elseif ($this->users->emailExists($email)) {
                $error = 'Cet email est deja utilise.';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $this->users->create($nom, $prenom, $email, $hash);
                $success = 'Compte cree avec succes ! Vous pouvez maintenant vous connecter.';
            }
        }

        $this->view('auth/register', [
            'error' => $error,
            'success' => $success,
            'old' => $old,
        ]);
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ../index.html');
        exit;
    }
}
