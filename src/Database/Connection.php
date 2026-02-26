<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    /**
     * Get a PDO instance connected to the target database.
     * Used by Migration and the rest of the app.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createPdo(
                $_ENV['DB_DATABASE'] ?? 'rental_db'
            );
        }

        return self::$instance;
    }

    /**
     * Get a PDO instance WITHOUT selecting a database.
     * Used by Migration::createDbIfNotExists() and Migration::drop()
     * so we can CREATE / DROP the database itself.
     */
    public static function raw(): PDO
    {
        return self::createPdo(null);
    }

    /**
     * Reset the singleton (useful after drop/fresh so the next
     * getInstance() reconnects to the freshly-created database).
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    // ── private ────────────────────────────────────────────────────────────────

    private static function createPdo(?string $dbName): PDO
    {
        $host    = $_ENV['DB_HOST']     ?? '127.0.0.1';
        $port    = $_ENV['DB_PORT']     ?? '3306';
        $user    = $_ENV['DB_USERNAME'] ?? 'root';
        $pass    = $_ENV['DB_PASSWORD'] ?? '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};charset={$charset}";

        if ($dbName !== null) {
            $dsn .= ";dbname={$dbName}";
        }

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            return $pdo;
        } catch (PDOException $e) {
            // Surface a clear message instead of a raw PDO dump
            $safeMsg = str_replace($pass, '***', $e->getMessage());
            throw new \RuntimeException("Database connection failed: {$safeMsg}", 0, $e);
        }
    }
}