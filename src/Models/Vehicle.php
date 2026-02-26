<?php

namespace App\Models;

use App\Database;

class Vehicle
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $sql = "SELECT v.*, 
                    COALESCE(
                        (SELECT image_path FROM vehicle_images WHERE vehicle_id = v.id AND is_primary = 1 LIMIT 1),
                        (SELECT image_path FROM vehicle_images WHERE vehicle_id = v.id ORDER BY id ASC LIMIT 1)
                    ) AS primary_image,
                    (SELECT COUNT(*) FROM vehicle_images WHERE vehicle_id = v.id) AS image_count
                FROM vehicles v
                WHERE 1=1";
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= " AND v.vehicle_type = :type";
            $params['type'] = $filters['type'];
        }

        if (!empty($filters['available'])) {
            $sql .= " AND v.is_available = 1";
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (v.vehicle_name LIKE :search OR v.brand LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY v.created_at DESC";

        if ($page > 0) {
            return $this->db->paginate($sql, $params, $page, $perPage);
        }

        return ['data' => $this->db->fetchAll($sql, $params)];
    }

    public function find(int $id): ?array
    {
        $vehicle = $this->db->fetch("SELECT * FROM vehicles WHERE id = :id", ['id' => $id]);
        if (!$vehicle) return null;

        $vehicle['images'] = $this->db->fetchAll(
            "SELECT * FROM vehicle_images WHERE vehicle_id = :id ORDER BY is_primary DESC, display_order ASC, id ASC",
            ['id' => $id]
        );

        // Set primary_image untuk kemudahan akses
        $vehicle['primary_image'] = $vehicle['images'][0]['image_path'] ?? null;

        return $vehicle;
    }

    public function findBySlug(string $slug): ?array
    {
        $vehicle = $this->db->fetch("SELECT * FROM vehicles WHERE slug = :slug", ['slug' => $slug]);
        if (!$vehicle) return null;

        $vehicle['images'] = $this->db->fetchAll(
            "SELECT * FROM vehicle_images WHERE vehicle_id = :id ORDER BY is_primary DESC, display_order ASC, id ASC",
            ['id' => $vehicle['id']]
        );

        $vehicle['primary_image'] = $vehicle['images'][0]['image_path'] ?? null;

        return $vehicle;
    }

    public function create(array $data): int
    {
        return $this->db->insert('vehicles', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('vehicles', $data, 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('vehicles', 'id = :id', ['id' => $id]);
    }

    public function addImage(int $vehicleId, string $imagePath, bool $isPrimary = false, int $order = 0): int
    {
        // Kalau belum ada gambar sama sekali, otomatis jadikan primary
        $existingCount = $this->db->count(
            "SELECT COUNT(*) FROM vehicle_images WHERE vehicle_id = :id",
            ['id' => $vehicleId]
        );

        if ($existingCount === 0) {
            $isPrimary = true;
        }

        if ($isPrimary) {
            $this->db->execute(
                "UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = :id",
                ['id' => $vehicleId]
            );
        }

        return $this->db->insert('vehicle_images', [
            'vehicle_id'    => $vehicleId,
            'image_path'    => $imagePath,
            'is_primary'    => $isPrimary ? 1 : 0,
            'display_order' => $order,
        ]);
    }

    public function setPrimaryImage(int $vehicleId, int $imageId): void
    {
        $this->db->execute(
            "UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = :vid",
            ['vid' => $vehicleId]
        );
        $this->db->execute(
            "UPDATE vehicle_images SET is_primary = 1 WHERE id = :id",
            ['id' => $imageId]
        );
    }

    public function deleteImage(int $imageId): int
    {
        // Kalau yang dihapus adalah primary, promote gambar berikutnya
        $img = $this->db->fetch(
            "SELECT * FROM vehicle_images WHERE id = :id",
            ['id' => $imageId]
        );

        $result = $this->db->delete('vehicle_images', 'id = :id', ['id' => $imageId]);

        if ($img && $img['is_primary']) {
            // Set gambar pertama yang tersisa sebagai primary
            $this->db->execute(
                "UPDATE vehicle_images SET is_primary = 1 
                 WHERE vehicle_id = :vid ORDER BY id ASC LIMIT 1",
                ['vid' => $img['vehicle_id']]
            );
        }

        return $result;
    }

    public function countByType(): array
    {
        return $this->db->fetchAll(
            "SELECT vehicle_type, COUNT(*) as count FROM vehicles GROUP BY vehicle_type"
        );
    }

    public function isAvailableForDates(int $vehicleId, string $startDate, string $endDate): bool
    {
        $vehicle = $this->db->fetch(
            "SELECT stock_quantity FROM vehicles WHERE id = :id",
            ['id' => $vehicleId]
        );
        if (!$vehicle) return false;

        $bookedCount = $this->db->count(
            "SELECT COUNT(*) FROM bookings 
             WHERE vehicle_id = :vid 
             AND booking_status NOT IN ('cancelled', 'completed')
             AND NOT (end_date < :start OR start_date > :end)",
            ['vid' => $vehicleId, 'start' => $startDate, 'end' => $endDate]
        );

        return $bookedCount < $vehicle['stock_quantity'];
    }
}
