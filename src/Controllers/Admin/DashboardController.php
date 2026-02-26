<?php

namespace App\Controllers\Admin;

use App\Models\Booking;
use App\Database;

class DashboardController
{
    public function index(): void
    {
        $booking = new Booking();
        $db = Database::getInstance();

        $stats = $booking->dashboardStats();
        $stats['total_vehicles']     = $db->count("SELECT COUNT(*) FROM vehicles");
        $stats['available_vehicles'] = $db->count("SELECT COUNT(*) FROM vehicles WHERE is_available = 1");

        $recentBookings = $booking->recentBookings(8);
        $settings = settings();

        // Revenue chart 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $revenue = $db->count(
                "SELECT COALESCE(SUM(total_price), 0)
                 FROM bookings
                 WHERE booking_status IN ('confirmed', 'ongoing', 'completed')
                 AND DATE(updated_at) = :d",
                ['d' => $date]
            );
            $chartData[] = [
                'date'    => date('d/m', strtotime($date)),
                'revenue' => $revenue,
            ];
        }

        view('admin.dashboard', compact('stats', 'recentBookings', 'settings', 'chartData'));
    }
}
