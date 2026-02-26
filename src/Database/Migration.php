<?php

namespace App\Database;

use PDO;

class Migration
{
    private PDO $pdo;
    private string $dbName;

    public function __construct()
    {
        $this->dbName = $_ENV['DB_DATABASE'] ?? 'rental_db';
    }

    private function createDbIfNotExists(): void
    {
        $pdo = Connection::raw();
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Database '{$this->dbName}' ready\n";
    }

    public function migrate(): void
    {
        $this->createDbIfNotExists();
        $this->pdo = Connection::getInstance();
        $this->runMigrations();
        echo "\n✅ Migration completed successfully!\n";
    }

    public function drop(): void
    {
        $pdo = Connection::raw();
        $pdo->exec("DROP DATABASE IF EXISTS `{$this->dbName}`");
        echo "✓ Database '{$this->dbName}' dropped\n";
        echo "\n✅ Drop completed successfully!\n";
    }

    public function fresh(): void
    {
        $this->drop();
        $this->migrate();
    }

    // ── Helper: cek apakah kolom sudah ada ────────────────────────────────────
    private function columnExists(string $table, string $column): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tbl AND COLUMN_NAME = :col
        ");
        $stmt->execute(['db' => $this->dbName, 'tbl' => $table, 'col' => $column]);
        return (bool) $stmt->fetchColumn();
    }

    // ── Helper: cek apakah tabel sudah ada ────────────────────────────────────
    private function tableExists(string $table): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tbl
        ");
        $stmt->execute(['db' => $this->dbName, 'tbl' => $table]);
        return (bool) $stmt->fetchColumn();
    }

    private function runMigrations(): void
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        // ── admin ──────────────────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `admin` (
                `id`            INT AUTO_INCREMENT PRIMARY KEY,
                `username`      VARCHAR(100)  NOT NULL UNIQUE,
                `password_hash` VARCHAR(255)  NOT NULL,
                `email`         VARCHAR(150)  NOT NULL UNIQUE,
                `created_at`    DATETIME      DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_username` (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: admin\n";

        // ── website_settings ───────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `website_settings` (
                `id`                        INT AUTO_INCREMENT PRIMARY KEY,
                `site_name`                 VARCHAR(200)  NOT NULL DEFAULT 'Rental Kendaraan',
                `tagline`                   VARCHAR(300),
                `meta_title`                TEXT,
                `meta_description`          TEXT,
                `meta_keywords`             TEXT,
                `canonical_url`             VARCHAR(500),
                `whatsapp_number`           VARCHAR(20)   NOT NULL DEFAULT '6281234567890',
                `whatsapp_message_template` TEXT,
                `primary_color`             VARCHAR(20)   DEFAULT '#3b82f6',
                `secondary_color`           VARCHAR(20)   DEFAULT '#8b5cf6',
                `footer_text`               TEXT,
                `address`                   TEXT,
                `phone`                     VARCHAR(20),
                `email`                     VARCHAR(150),
                `updated_at`                DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by`                INT,
                FOREIGN KEY (`updated_by`) REFERENCES `admin`(`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: website_settings\n";

        // Kolom baru website_settings — cek dulu sebelum tambah
        if (!$this->columnExists('website_settings', 'google_analytics_id')) {
            $this->pdo->exec("ALTER TABLE `website_settings` ADD COLUMN `google_analytics_id` VARCHAR(50) DEFAULT NULL COMMENT 'GA4 Measurement ID, e.g. G-XXXXXXXXXX'");
            echo "  + Kolom baru: website_settings.google_analytics_id\n";
        }
        if (!$this->columnExists('website_settings', 'facebook_pixel_id')) {
            $this->pdo->exec("ALTER TABLE `website_settings` ADD COLUMN `facebook_pixel_id` VARCHAR(50) DEFAULT NULL COMMENT 'Facebook Pixel ID'");
            echo "  + Kolom baru: website_settings.facebook_pixel_id\n";
        }

        // ── logo ───────────────────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `logo` (
                `id`           INT AUTO_INCREMENT PRIMARY KEY,
                `setting_id`   INT          NOT NULL,
                `logo_path`    VARCHAR(500),
                `favicon_path` VARCHAR(500),
                `is_active`    BOOLEAN      DEFAULT TRUE,
                `uploaded_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`setting_id`) REFERENCES `website_settings`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: logo\n";

        // ── seo ────────────────────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `seo` (
                `id`               INT AUTO_INCREMENT PRIMARY KEY,
                `setting_id`       INT          NOT NULL,
                `page_name`        VARCHAR(100) NOT NULL,
                `page_slug`        VARCHAR(100) NOT NULL,
                `meta_title`       VARCHAR(300),
                `meta_description` TEXT,
                `meta_keywords`    TEXT,
                `og_image_path`    VARCHAR(500),
                `updated_at`       DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`setting_id`) REFERENCES `website_settings`(`id`) ON DELETE CASCADE,
                UNIQUE KEY `uk_page_slug` (`page_slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: seo\n";

        // ── vehicles ───────────────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `vehicles` (
                `id`                 INT AUTO_INCREMENT PRIMARY KEY,
                `vehicle_name`       VARCHAR(200)  NOT NULL,
                `vehicle_type`       ENUM('mobil','motor','bus','truk') NOT NULL DEFAULT 'mobil',
                `slug`               VARCHAR(250)  NOT NULL UNIQUE,
                `description`        TEXT,
                `price_per_day`      DECIMAL(12,0) NOT NULL DEFAULT 0,
                `price_per_week`     DECIMAL(12,0) DEFAULT 0,
                `price_per_month`    DECIMAL(12,0) DEFAULT 0,
                `stock_quantity`     INT           NOT NULL DEFAULT 1,
                `year`               INT,
                `brand`              VARCHAR(100),
                `transmission`       ENUM('manual','otomatis') DEFAULT 'manual',
                `fuel_type`          ENUM('bensin','solar','listrik','hybrid') DEFAULT 'bensin',
                `passenger_capacity` INT           DEFAULT 4,
                `features`           TEXT          COMMENT 'JSON array of features',
                `is_available`       BOOLEAN       DEFAULT TRUE,
                `created_at`         DATETIME      DEFAULT CURRENT_TIMESTAMP,
                `updated_at`         DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `created_by`         INT,
                FOREIGN KEY (`created_by`) REFERENCES `admin`(`id`) ON DELETE SET NULL,
                INDEX `idx_type`      (`vehicle_type`),
                INDEX `idx_available` (`is_available`),
                INDEX `idx_slug`      (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: vehicles\n";

        // ── vehicle_images ─────────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `vehicle_images` (
                `id`            INT AUTO_INCREMENT PRIMARY KEY,
                `vehicle_id`    INT          NOT NULL,
                `image_path`    VARCHAR(500) NOT NULL,
                `is_primary`    BOOLEAN      DEFAULT FALSE,
                `display_order` INT          DEFAULT 0,
                `uploaded_at`   DATETIME     DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE CASCADE,
                INDEX `idx_vehicle_id` (`vehicle_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: vehicle_images\n";

        // ── bookings ───────────────────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `bookings` (
                `id`                    INT AUTO_INCREMENT PRIMARY KEY,
                `booking_code`          VARCHAR(50)   NOT NULL UNIQUE,
                `vehicle_id`            INT           NOT NULL,
                `customer_name`         VARCHAR(200)  NOT NULL,
                `customer_phone`        VARCHAR(20)   NOT NULL,
                `customer_email`        VARCHAR(150),
                `start_date`            DATE          NOT NULL,
                `end_date`              DATE          NOT NULL,
                `duration_days`         INT           GENERATED ALWAYS AS (DATEDIFF(`end_date`, `start_date`)) STORED,
                `pickup_location`       TEXT,
                `return_location`       TEXT,
                `special_requests`      TEXT,
                `total_price`           DECIMAL(15,0) NOT NULL DEFAULT 0,
                `booking_status`        ENUM('pending','confirmed','ongoing','completed','cancelled') DEFAULT 'pending',
                `booking_date`          DATETIME      DEFAULT CURRENT_TIMESTAMP,
                `whatsapp_chat_id`      VARCHAR(100),
                `whatsapp_conversation` TEXT,
                `notes`                 TEXT          COMMENT 'Admin notes',
                `updated_at`            DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE RESTRICT,
                INDEX `idx_booking_code`  (`booking_code`),
                INDEX `idx_status`        (`booking_status`),
                INDEX `idx_booking_date`  (`booking_date`),
                INDEX `idx_vehicle_date`  (`vehicle_id`, `start_date`, `end_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: bookings\n";

        // ── payment_confirmations ──────────────────────────────────────────────
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `payment_confirmations` (
                `id`                 INT AUTO_INCREMENT PRIMARY KEY,
                `booking_id`         INT           NOT NULL,
                `payment_proof_path` VARCHAR(500),
                `amount_paid`        DECIMAL(15,0) NOT NULL DEFAULT 0,
                `payment_method`     ENUM('transfer_bank','cash','ewallet','qris') DEFAULT 'transfer_bank',
                `bank_name`          VARCHAR(100),
                `account_number`     VARCHAR(100),
                `payment_date`       DATETIME      DEFAULT CURRENT_TIMESTAMP,
                `confirmed_by`       VARCHAR(100),
                `confirmed_at`       DATETIME,
                `status`             ENUM('pending','verified','rejected') DEFAULT 'pending',
                `notes`              TEXT,
                FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
                INDEX `idx_booking_id` (`booking_id`),
                INDEX `idx_status`     (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✓ Table: payment_confirmations\n";

        // ── view: v_booking_summary ────────────────────────────────────────────
        $this->pdo->exec("
            CREATE OR REPLACE VIEW `v_booking_summary` AS
            SELECT
                b.id, b.booking_code, b.customer_name, b.customer_phone,
                v.vehicle_name, v.vehicle_type,
                b.start_date, b.end_date, b.duration_days,
                b.total_price, b.booking_status, b.booking_date,
                pc.status AS payment_status, pc.amount_paid
            FROM bookings b
            JOIN vehicles v ON b.vehicle_id = v.id
            LEFT JOIN payment_confirmations pc ON b.id = pc.booking_id
        ");
        echo "✓ View: v_booking_summary\n";

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        $this->seedAdmin();
        $this->seedSettings();
        $this->seedVehicles();
    }

    // ── Seeders ────────────────────────────────────────────────────────────────

    private function seedAdmin(): void
    {
        $count = $this->pdo->query("SELECT COUNT(*) FROM `admin`")->fetchColumn();
        if ($count == 0) {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $this->pdo->prepare("INSERT INTO `admin` (`username`, `password_hash`, `email`) VALUES (?, ?, ?)")
                      ->execute(['admin', $hash, 'admin@rental.com']);
            echo "✓ Seeded admin (admin / admin123)\n";
        }
    }

    private function seedSettings(): void
    {
        $count = $this->pdo->query("SELECT COUNT(*) FROM `website_settings`")->fetchColumn();
        if ($count == 0) {
            $template = "Halo, saya ingin booking kendaraan:\n\n"
                . "*Kode Booking*: {booking_code}\n"
                . "*Nama*: {customer_name}\n"
                . "*No HP*: {customer_phone}\n"
                . "*Tanggal*: {start_date} s/d {end_date}\n"
                . "*Total*: {total_price}\n\n"
                . "Mohon konfirmasi ketersediaan. Terima kasih.";

            $this->pdo->prepare("
                INSERT INTO `website_settings`
                    (`site_name`, `tagline`, `meta_title`, `meta_description`,
                     `whatsapp_number`, `whatsapp_message_template`, `primary_color`)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                'Rental Kendaraan', 'Solusi Transportasi Terpercaya',
                'Rental Kendaraan - Sewa Mobil & Motor Murah',
                'Jasa rental kendaraan terpercaya. Sewa mobil dan motor dengan harga terjangkau.',
                '6281234567890', $template, '#3b82f6',
            ]);

            $settingId = $this->pdo->lastInsertId();
            $this->pdo->prepare("INSERT INTO `logo` (`setting_id`, `logo_path`, `favicon_path`, `is_active`) VALUES (?, NULL, NULL, TRUE)")
                      ->execute([$settingId]);

            $seoStmt = $this->pdo->prepare("INSERT INTO `seo` (`setting_id`, `page_name`, `page_slug`, `meta_title`, `meta_description`) VALUES (?, ?, ?, ?, ?)");
            foreach ([
                ['Beranda',          'home',        'Rental Kendaraan - Sewa Mobil & Motor Terpercaya', 'Jasa rental kendaraan terpercaya dengan harga terjangkau.'],
                ['Daftar Kendaraan', 'vehicles',    'Daftar Kendaraan Tersedia - Rental Kendaraan',     'Lihat semua kendaraan yang tersedia untuk disewa.'],
                ['Cara Booking',     'how-to-book', 'Cara Booking - Rental Kendaraan',                  'Panduan cara memesan kendaraan rental dengan mudah via WhatsApp.'],
                ['Kontak',           'contact',     'Kontak Kami - Rental Kendaraan',                   'Hubungi kami untuk informasi lebih lanjut.'],
            ] as $page) {
                $seoStmt->execute([$settingId, $page[0], $page[1], $page[2], $page[3]]);
            }
            echo "✓ Seeded website_settings, logo, seo\n";
        }
    }

    private function seedVehicles(): void
    {
        $count = $this->pdo->query("SELECT COUNT(*) FROM `vehicles`")->fetchColumn();
        if ($count == 0) {
            $adminId = $this->pdo->query("SELECT id FROM `admin` LIMIT 1")->fetchColumn();
            $stmt = $this->pdo->prepare("
                INSERT INTO `vehicles`
                    (`vehicle_name`, `vehicle_type`, `slug`, `description`,
                     `price_per_day`, `price_per_week`, `price_per_month`,
                     `transmission`, `passenger_capacity`, `brand`, `year`, `is_available`, `created_by`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?)
            ");
            foreach ([
                ['Toyota Avanza 2022', 'mobil', 'toyota-avanza-2022', 'Toyota Avanza 2022, kondisi prima dan terawat.', 350000, 2000000, 7000000,  'manual',   7, 'Toyota', 2022],
                ['Honda Beat 2023',    'motor', 'honda-beat-2023',    'Honda Beat 2023, irit bahan bakar.',              75000,  450000,  1500000,  'otomatis', 2, 'Honda',  2023],
                ['Toyota Innova 2021', 'mobil', 'toyota-innova-2021', 'Toyota Innova 2021, luas dan nyaman.',           500000, 3000000, 10000000, 'manual',   8, 'Toyota', 2021],
            ] as $v) {
                $stmt->execute([...$v, $adminId]);
            }
            echo "✓ Seeded vehicles (3 records)\n";
        }
    }
}
