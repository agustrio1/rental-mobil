<?php

namespace App\Controllers\Admin;

use App\Models\Booking;

class BookingController
{
    private Booking $booking;

    public function __construct()
    {
        $this->booking = new Booking();
    }

    public function index(): void
    {
        $filters = [
            'status'    => $_GET['status'] ?? '',
            'search'    => $_GET['search'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to'   => $_GET['date_to'] ?? '',
        ];
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->booking->all($filters, $page, 15);

        view('admin.bookings.index', [
            'bookings'   => $result['data'],
            'pagination' => $result,
            'filters'    => $filters,
            'settings'   => settings(),
        ]);
    }

    public function show(array $params): void
    {
        $booking = $this->booking->find((int)$params['id']);
        if (!$booking) {
            flash('error', 'Booking tidak ditemukan');
            redirect('/admin/bookings');
        }

        view('admin.bookings.show', ['booking' => $booking, 'settings' => settings()]);
    }

    public function updateStatus(array $params): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token tidak valid');
            redirect('/admin/bookings/' . $params['id']);
            return;
        }

        $id     = (int)$params['id'];
        $status = $_POST['status'] ?? '';
        $notes  = trim($_POST['notes'] ?? '');

        $validStatuses = ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            flash('error', 'Status tidak valid');
            redirect('/admin/bookings/' . $id);
            return;
        }

        $this->booking->updateStatus($id, $status, $notes ?: null);

        flash('success', 'Status booking berhasil diupdate');
        redirect("/admin/bookings/{$id}");
    }
}
