#!/usr/bin/env php
<?php

// Load autoloader
require __DIR__ . '/vendor/autoload.php';

// Load .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

use App\Database\Migration;

$command = $argv[1] ?? 'help';

$art = <<<LOGO

  ╔═══════════════════════════════╗
  ║   Rental Mobile - Artisan  ║
  ╚═══════════════════════════════╝

LOGO;

echo $art;

switch ($command) {
    case 'migrate':
        echo "🚀 Running migrations...\n\n";
        $migration = new Migration();
        $migration->migrate();
        break;

    case 'migrate:drop':
        echo "⚠️  Dropping database...\n\n";
        $migration = new Migration();
        $migration->drop();
        break;

    case 'migrate:fresh':
        echo "🔄 Fresh migration (drop + migrate)...\n\n";
        $migration = new Migration();
        $migration->fresh();
        break;

    case 'serve':
        $port = $argv[2] ?? '8000';
        echo "🌐 Starting PHP dev server on http://localhost:{$port}\n";
        echo "Press Ctrl+C to stop.\n\n";
        passthru("php -S localhost:{$port} -t public public/index.php");
        break;

    case 'help':
    default:
        echo "Available commands:\n";
        echo "  php artisan.php migrate         - Run database migrations\n";
        echo "  php artisan.php migrate:drop    - Drop the database\n";
        echo "  php artisan.php migrate:fresh   - Drop and re-run migrations\n";
        echo "  php artisan.php serve [port]    - Start development server\n";
        break;
}
