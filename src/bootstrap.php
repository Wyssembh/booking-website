<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});

if (!function_exists('app_url')) {
    function app_url(string $route = ''): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/src/index.php';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $indexPath = ($basePath === '' ? '' : $basePath) . '/index.php';

        if ($route === '') {
            return $indexPath;
        }

        return $indexPath . '?route=' . urlencode($route);
    }
}

if (!function_exists('project_web_root')) {
    function project_web_root(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/src/index.php';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if ($basePath === '' || $basePath === '.') {
            return '';
        }

        if (preg_match('#/src$#', $basePath)) {
            $root = preg_replace('#/src$#', '', $basePath);

            return $root === '' ? '' : $root;
        }

        return $basePath;
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $root = project_web_root();

        if ($root === '') {
            return '/' . $path;
        }

        return $root . '/' . $path;
    }
}

if (!function_exists('seabel_logo_url')) {
    function seabel_logo_url(): string
    {
        return asset_url('src/uploads/aa.png');
    }
}

if (!function_exists('slugify')) {
    function slugify(string $value): string
    {
        $value = trim(strtolower($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');

        return $value !== '' ? $value : 'hotel';
    }
}

if (!function_exists('stars_image_url')) {
    function stars_image_url(int $stars): string
    {
        if ($stars >= 4) {
            return 'https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/4_Stars-7.png';
        }

        return 'https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/3_Stars-8.png';
    }
}

if (!function_exists('car_image_src')) {
    /** Chemin stocke en BDD : uploads/cars/fichier.ext (ou anciennement seulement le nom du fichier). */
    function car_image_src(?string $stored): ?string
    {
        if ($stored === null || $stored === '') {
            return null;
        }

        $stored = str_replace('\\', '/', trim($stored));
        if ($stored === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $stored)) {
            return $stored;
        }

        $stored = ltrim($stored, '/');
        $stored = preg_replace('#^src/#', '', $stored) ?? $stored;

        if (strpos($stored, '/') === false) {
            $stored = 'uploads/cars/' . $stored;
        }

        if (strpos($stored, 'uploads/') === 0) {
            $stored = 'src/' . $stored;
        }

        return asset_url($stored);
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to(string $route): void
    {
        header('Location: ' . app_url($route));
        exit;
    }
}
