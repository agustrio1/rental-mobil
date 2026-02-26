<?php

namespace App\Models;

use App\Database;

class Booking
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $sql = "SELECT b.*, v.vehicle_name, v.vehicle_type,
                    pc.status AS payment_status, pc.amount_paid
                FROM bookings b
                JOIN vehicles v ON b.vehicle_id = v.id
                LEFT JOIN payment_confirmations pc ON b.id = pc.booking_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND b.booking_status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (b.booking_code LIKE :search OR b.customer_name LIKE :search OR b.customer_phone LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(b.booking_date) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(b.booking_date) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }

        $sql .= " ORDER BY b.booking_date DESC";

        return $this->db->paginate($sql, $params, $page, $perPage);
    }

    public function find(int $id): ?array
    {
        $booking = $this->db->fetch(
            "SELECT b.*, v.vehicle_name, v.vehicle_type, v.price_per_day,
                    pc.id AS pc_id, pc.status AS payment_status, pc.amount_paid AS payment_amount,
                    pc.payment_method, pc.payment_proof_path AS payment_proof, pc.payment_date,
                    pc.confirmed_by, pc.confirmed_at, pc.notes AS payment_notes,
                    (SELECT image_path FROM vehicle_images WHERE vehicle_id = v.id AND is_primary = 1 LIMIT 1) AS vehicle_image
             FROM bookings b
             JOIN vehicles v ON b.vehicle_id = v.id
             LEFT JOIN payment_confirmations pc ON b.id = pc.booking_id
             WHERE b.id = :id",
            ['id' => $id]
        );
        return $booking ?: null;
    }

    public function findByCode(string $code): ?array
    {
        return $this->db->fetch(
            "SELECT b.*, v.vehicle_name FROM bookings b 
             JOIN vehicles v ON b.vehicle_id = v.id 
             WHERE b.booking_code = :code",
            ['code' => $code]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('bookings', $data);
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): int
    {
        $data = ['booking_status' => $status];
        if ($notes !== null) $data['notes'] = $notes;
        return $this->db->update('bookings', $data, 'id = :id', ['id' => $id]);
    }

    public function dashboardStats(): array
    {
        return [
            'total' => $this->db->count(
                "SELECT COUNT(*) FROM bookings"
            ),
            'today' => $this->db->count(
                "SELECT COUNT(*) FROM bookings WHERE DATE(booking_date) = CURDATE()"
            ),
            'pending' => $this->db->count(
                "SELECT COUNT(*) FROM bookings WHERE booking_status = 'pending'"
            ),
            'confirmed' => $this->db->count(
                "SELECT COUNT(*) FROM bookings WHERE booking_status = 'confirmed'"
            ),
            'ongoing' => $this->db->count(
                "SELECT COUNT(*) FROM bookings WHERE booking_status = 'ongoing'"
            ),

            // Revenue = semua booking yang sudah dikonfirmasi (bukan pending/cancelled)
            // karena bayar di awal langsung ke pemilik, confirmed = sudah bayar
            'revenue_month' => $this->db->count(
                "SELECT COALESCE(SUM(total_price), 0)
                 FROM bookings
                 WHERE booking_status IN ('confirmed', 'ongoing', 'completed')
                 AND MONTH(updated_at) = MONTH(CURDATE())
                 AND YEAR(updated_at) = YEAR(CURDATE())"
            ),
        ];
    }

    public function recentBookings(int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT b.*, v.vehicle_name FROM bookings b 
             JOIN vehicles v ON b.vehicle_id = v.id 
             ORDER BY b.booking_date DESC LIMIT :limit",
            ['limit' => $limit]
        );
    }
}
