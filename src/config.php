<?php
$envFile = __DIR__ . '/../.env';
$env = is_file($envFile) ? parse_ini_file($envFile) : [];
if (!is_array($env)) {
    $env = [];
}

function getConfigValue($key, array $env, $default = '') {
    $value = getenv($key);

    if ($value === false || $value === '') {
        $value = $env[$key] ?? '';
    }

    if (is_string($value)) {
        $value = trim($value, " \t\n\r\0\x0B\"");
    }

    return $value === '' ? $default : $value;
}

define('DB_HOST', getConfigValue('DB_HOST', $env, '127.0.0.1'));
define('DB_USER', getConfigValue('DB_USER', $env, 'root'));
define('DB_PASS', getConfigValue('DB_PASS', $env, ''));
define('DB_NAME', getConfigValue('DB_NAME', $env, 'test'));

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Erreur de connexion a la base de donnees. Verifiez DB_HOST, DB_USER, DB_PASS et DB_NAME dans .env.');
}

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?route=login');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php?route=login');
        exit;
    }
}
