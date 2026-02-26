<?php

namespace App\Models;

use App\Database;

class Setting
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function get(): ?array
    {
        return $this->db->fetch(
            "SELECT ws.*, l.id AS logo_id, l.logo_path, l.favicon_path 
             FROM website_settings ws 
             LEFT JOIN logo l ON l.setting_id = ws.id AND l.is_active = 1
             LIMIT 1"
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('website_settings', $data, 'id = :id', ['id' => $id]);
    }

    public function updateLogo(int $settingId, array $data): void
    {
        $existing = $this->db->fetch("SELECT id FROM logo WHERE setting_id = :sid LIMIT 1", ['sid' => $settingId]);

        if ($existing) {
            $this->db->update('logo', $data, 'setting_id = :sid', ['sid' => $settingId]);
        } else {
            $data['setting_id'] = $settingId;
            $this->db->insert('logo', $data);
        }
    }

    public function getSeoPages(): array
    {
        return $this->db->fetchAll("SELECT * FROM seo ORDER BY id ASC");
    }

    public function getSeoBySlug(string $slug): ?array
    {
        return $this->db->fetch("SELECT * FROM seo WHERE page_slug = :slug", ['slug' => $slug]);
    }

    public function updateSeo(int $id, array $data): int
    {
        return $this->db->update('seo', $data, 'id = :id', ['id' => $id]);
    }

    public function createSeo(array $data): int
    {
        return $this->db->insert('seo', $data);
    }

    /**
     * Generate color palette from hex color
     */
    public static function generateColorPalette(string $hex): array
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $palette = [];

        // Generate shades (50 = lightest, 900 = darkest)
        $shades = [
            50  => 0.95,
            100 => 0.88,
            200 => 0.75,
            300 => 0.60,
            400 => 0.40,
            500 => 0.0,  // base color
            600 => -0.10,
            700 => -0.22,
            800 => -0.35,
            900 => -0.50,
        ];

        foreach ($shades as $shade => $factor) {
            if ($factor > 0) {
                // Lighter - mix with white
                $nr = (int)min(255, $r + ($r < 200 ? ($factor * (255 - $r)) : $factor * 255));
                $ng = (int)min(255, $g + ($g < 200 ? ($factor * (255 - $g)) : $factor * 255));
                $nb = (int)min(255, $b + ($b < 200 ? ($factor * (255 - $b)) : $factor * 255));
            } elseif ($factor < 0) {
                // Darker - reduce brightness
                $nr = (int)max(0, $r * (1 + $factor));
                $ng = (int)max(0, $g * (1 + $factor));
                $nb = (int)max(0, $b * (1 + $factor));
            } else {
                $nr = $r; $ng = $g; $nb = $b;
            }

            $palette[$shade] = sprintf('#%02x%02x%02x', $nr, $ng, $nb);
        }

        return $palette;
    }
}