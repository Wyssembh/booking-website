<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = (string) (getenv('DB_HOST') ?: '');
        $user = (string) (getenv('DB_USER') ?: '');
        $passValue = getenv('DB_PASS');
        $pass = $passValue === false ? '' : (string) $passValue;
        $name = (string) (getenv('DB_NAME') ?: '');

        if ($host === '' || $user === '' || $name === '') {
            $envPath = dirname(__DIR__, 3) . '/.env';
            if (is_file($envPath)) {
                $env = parse_ini_file($envPath, false, INI_SCANNER_TYPED) ?: [];

                if ($host === '') {
                    $host = (string) ($env['DB_HOST'] ?? 'localhost');
                }
                if ($user === '') {
                    $user = (string) ($env['DB_USER'] ?? 'root');
                }
                if ($name === '') {
                    $name = (string) ($env['DB_NAME'] ?? 'test');
                }
                if ($pass === '' && array_key_exists('DB_PASS', $env)) {
                    $pass = (string) $env['DB_PASS'];
                }
            }
        }

        $host = $host === '' ? 'localhost' : $host;
        $user = $user === '' ? 'root' : $user;
        $name = $name === '' ? 'test' : $name;

        self::$connection = new PDO(
            sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $name),
            (string) $user,
            (string) $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        return self::$connection;
    }
}
