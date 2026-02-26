<?php

namespace App\Controllers\Public;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Database;

class BookingController
{
    public function store(): void
    {
        $vehicleId = (int)($_POST['vehicle_id'] ?? 0);
        $vehicle = (new Vehicle())->find($vehicleId);

        if (!$vehicle) {
            json_response(['success' => false, 'message' => 'Kendaraan tidak ditemukan'], 404);
        }

        // Validate
        $required = ['customer_name', 'customer_phone', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                json_response(['success' => false, 'message' => 'Field ' . $field . ' harus diisi'], 422);
            }
        }

        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        if (strtotime($startDate) >= strtotime($endDate)) {
            json_response(['success' => false, 'message' => 'Tanggal selesai harus setelah tanggal mulai'], 422);
        }

        // Check availability
        if (!(new Vehicle())->isAvailableForDates($vehicleId, $startDate, $endDate)) {
            json_response(['success' => false, 'message' => 'Kendaraan tidak tersedia untuk tanggal tersebut'], 409);
        }

        $bookingCode = generate_booking_code();

        $data = [
            'booking_code'     => $bookingCode,
            'vehicle_id'       => $vehicleId,
            'customer_name'    => trim($_POST['customer_name']),
            'customer_phone'   => preg_replace('/\D/', '', $_POST['customer_phone']),
            'customer_email'   => trim($_POST['customer_email'] ?? ''),
            'start_date'       => $startDate,
            'end_date'         => $endDate,
            'pickup_location'  => trim($_POST['pickup_location'] ?? ''),
            'return_location'  => trim($_POST['return_location'] ?? ''),
            'special_requests' => trim($_POST['special_requests'] ?? ''),
            'total_price'      => (int)($_POST['total_price'] ?? 0),
            'booking_status'   => 'pending',
        ];

        $bookingModel = new Booking();
        $id = $bookingModel->create($data);

        $settings = settings();
        $template = $settings['whatsapp_message_template'];
        $waNumber = $settings['whatsapp_number'];

        json_response([
            'success'            => true,
            'booking_code'       => $bookingCode,
            'booking_id'         => $id,
            'whatsapp_number'    => $waNumber,
            'whatsapp_template'  => $template,
            'message'            => 'Booking berhasil! Silakan lanjutkan ke WhatsApp.',
        ]);
    }

    public function confirm(): void
    {
        // Page: konfirmasi pembayaran
        $bookingCode = $_GET['code'] ?? '';
        $settings = settings();
        $booking = null;

        if ($bookingCode) {
            $booking = (new Booking())->findByCode($bookingCode);
        }

        view('public.booking.confirm', compact('booking', 'settings', 'bookingCode'));
    }

    public function submitPayment(): void
    {
        $bookingCode = $_POST['booking_code'] ?? '';
        $booking = (new Booking())->findByCode($bookingCode);

        if (!$booking) {
            json_response(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        $paymentProofPath = null;
        if (!empty($_FILES['payment_proof']['name'])) {
            $paymentProofPath = upload_file($_FILES['payment_proof'], 'payments');
        }

        $db = Database::getInstance();
        $db->insert('payment_confirmations', [
            'booking_id'         => $booking['id'],
            'payment_proof_path' => $paymentProofPath,
            'amount_paid'        => (int)($_POST['amount_paid'] ?? 0),
            'payment_method'     => $_POST['payment_method'] ?? 'transfer_bank',
            'bank_name'          => trim($_POST['bank_name'] ?? ''),
            'account_number'     => trim($_POST['account_number'] ?? ''),
            'status'             => 'pending',
        ]);

        json_response([
            'success' => true,
            'message' => 'Konfirmasi pembayaran berhasil dikirim. Admin akan memverifikasi dalam 1x24 jam.',
        ]);
    }
}