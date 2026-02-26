<?php

/**
 * Entry Point - public/index.php
 * Semua request diarahkan ke sini
 */

define('BASE_PATH', dirname(__DIR__));

// Load composer autoloader
if (!file_exists(BASE_PATH . '/vendor/autoload.php')) {
    die('Please run: composer install');
}

require BASE_PATH . '/vendor/autoload.php';

// Load .env
if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

// Konfigurasi timezone
$config = require BASE_PATH . '/config/app.php';
date_default_timezone_set($config['timezone']);

// Error handling
if ($config['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Start session
session_name($config['session']['name']);
session_set_cookie_params([
    'lifetime' => $config['session']['lifetime'],
    'path'     => '/',
    'secure'   => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// CSRF Token generation
if (empty($_SESSION['_csrf'])) {
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));
}

// Load and dispatch routes
$router = require BASE_PATH . '/routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$router->dispatch($method, $uri);