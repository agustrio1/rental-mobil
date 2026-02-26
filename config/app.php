<?php

// Load .env if exists
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

return [
    'name'     => $_ENV['APP_NAME'] ?? 'Rental Kendaraan',
    'url'      => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'env'      => $_ENV['APP_ENV'] ?? 'production',
    'debug'    => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta',
    
    'db' => [
        'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port'     => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_DATABASE'] ?? 'rental_db',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
    ],
    
    'session' => [
        'name'     => $_ENV['SESSION_NAME'] ?? 'rental_session',
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
    ],
    
    'upload' => [
        'max_size' => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880), // 5MB
        'path'     => dirname(__DIR__) . '/' . ($_ENV['UPLOAD_PATH'] ?? 'public/uploads'),
        'url_path' => '/uploads',
    ],
];