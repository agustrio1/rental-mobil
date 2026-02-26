<?php

namespace App\Controllers\Public;

use App\Models\Vehicle;
use App\Models\Setting;

class VehicleController
{
    private Vehicle $vehicle;

    public function __construct()
    {
        $this->vehicle = new Vehicle();
    }

    public function index(): void
    {
        $filters = [
            'type'      => $_GET['type'] ?? '',
            'search'    => $_GET['search'] ?? '',
            'available' => true,
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->vehicle->all($filters, $page, 9);
        $settings = settings();
        $seo = (new Setting())->getSeoBySlug('vehicles');

        view('public.vehicles.index', [
            'vehicles'   => $result['data'],
            'pagination' => $result,
            'filters'    => $filters,
            'settings'   => $settings,
            'seo'        => $seo,
        ]);
    }

    public function show(array $params): void
    {
        $vehicle = $this->vehicle->findBySlug($params['slug']);

        if (!$vehicle) {
            http_response_code(404);
            view('errors.404', ['settings' => settings()]);
            return;
        }

        $settings = settings();

        view('public.vehicles.show', compact('vehicle', 'settings'));
    }

    public function checkAvailability(): void
    {
        $vehicleId = (int)($_POST['vehicle_id'] ?? 0);
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';

        if (!$vehicleId || !$startDate || !$endDate) {
            json_response(['available' => false, 'message' => 'Data tidak lengkap'], 422);
        }

        $available = $this->vehicle->isAvailableForDates($vehicleId, $startDate, $endDate);

        json_response([
            'available' => $available,
            'message'   => $available ? 'Kendaraan tersedia!' : 'Kendaraan tidak tersedia untuk tanggal tersebut',
        ]);
    }
}