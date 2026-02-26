<?php

namespace App\Controllers\Admin;

use App\Models\Vehicle;

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
            'search' => $_GET['search'] ?? '',
            'type'   => $_GET['type'] ?? '',
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->vehicle->all($filters, $page, 15);

        view('admin.vehicles.index', [
            'vehicles'   => $result['data'],
            'pagination' => $result,
            'filters'    => $filters,
            'settings'   => settings(),
        ]);
    }

    public function create(): void
    {
        view('admin.vehicles.form', ['vehicle' => null, 'settings' => settings()]);
    }

    public function store(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token tidak valid');
            redirect('/admin/vehicles/create');
        }

        $data = $this->validateAndSanitize($_POST);

        if (isset($data['error'])) {
            flash('error', $data['error']);
            redirect('/admin/vehicles/create');
        }

        $data['created_by'] = auth()['id'];

        $id = $this->vehicle->create($data);

        // Handle image upload
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $idx => $tmpName) {
                if ($_FILES['images']['error'][$idx] === UPLOAD_ERR_OK) {
                    $file = [
                        'name'     => $_FILES['images']['name'][$idx],
                        'type'     => $_FILES['images']['type'][$idx],
                        'tmp_name' => $tmpName,
                        'error'    => $_FILES['images']['error'][$idx],
                        'size'     => $_FILES['images']['size'][$idx],
                    ];
                    $path = upload_file($file, 'vehicles');
                    if ($path) {
                        $this->vehicle->addImage($id, $path, $idx === 0);
                    }
                }
            }
        }

        flash('success', 'Kendaraan berhasil ditambahkan!');
        redirect('/admin/vehicles');
    }

    public function edit(array $params): void
    {
        $vehicle = $this->vehicle->find((int)$params['id']);
        if (!$vehicle) {
            flash('error', 'Kendaraan tidak ditemukan');
            redirect('/admin/vehicles');
        }

        view('admin.vehicles.form', ['vehicle' => $vehicle, 'settings' => settings()]);
    }

    public function update(array $params): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token tidak valid');
            back();
        }

        $id = (int)$params['id'];
        $data = $this->validateAndSanitize($_POST, $id);

        if (isset($data['error'])) {
            flash('error', $data['error']);
            redirect("/admin/vehicles/{$id}/edit");
        }

        $this->vehicle->update($id, $data);

        // Handle new image uploads
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $idx => $tmpName) {
                if ($_FILES['images']['error'][$idx] === UPLOAD_ERR_OK) {
                    $file = [
                        'name'     => $_FILES['images']['name'][$idx],
                        'type'     => $_FILES['images']['type'][$idx],
                        'tmp_name' => $tmpName,
                        'error'    => $_FILES['images']['error'][$idx],
                        'size'     => $_FILES['images']['size'][$idx],
                    ];
                    $path = upload_file($file, 'vehicles');
                    if ($path) {
                        $this->vehicle->addImage($id, $path);
                    }
                }
            }
        }

        flash('success', 'Kendaraan berhasil diupdate!');
        redirect('/admin/vehicles');
    }

    public function deleteImage(array $params): void
    {
        $this->vehicle->deleteImage((int)$params['id']);
        json_response(['success' => true]);
    }

    public function delete(array $params): void
    {
        $this->vehicle->delete((int)$params['id']);
        flash('success', 'Kendaraan berhasil dihapus');
        redirect('/admin/vehicles');
    }

    public function toggleAvailability(array $params): void
    {
        $vehicle = $this->vehicle->find((int)$params['id']);
        if ($vehicle) {
            $this->vehicle->update($vehicle['id'], ['is_available' => $vehicle['is_available'] ? 0 : 1]);
        }
        json_response(['success' => true]);
    }

    private function validateAndSanitize(array $data, ?int $excludeId = null): array
    {
        $name = trim($data['vehicle_name'] ?? '');
        if (empty($name)) return ['error' => 'Nama kendaraan harus diisi'];

        $slug = slugify($name);
        // Make slug unique
        $db = \App\Database::getInstance();
        $existing = $db->fetch(
            "SELECT id FROM vehicles WHERE slug = :slug" . ($excludeId ? " AND id != :eid" : ""),
            array_merge(['slug' => $slug], $excludeId ? ['eid' => $excludeId] : [])
        );
        if ($existing) {
            $slug .= '-' . time();
        }

        return [
            'vehicle_name'       => $name,
            'vehicle_type'       => $data['vehicle_type'] ?? 'mobil',
            'slug'               => $slug,
            'description'        => trim($data['description'] ?? ''),
            'price_per_day'      => (int)str_replace(['.', ','], '', $data['price_per_day'] ?? 0),
            'price_per_week'     => (int)str_replace(['.', ','], '', $data['price_per_week'] ?? 0),
            'price_per_month'    => (int)str_replace(['.', ','], '', $data['price_per_month'] ?? 0),
            'stock_quantity'     => max(1, (int)($data['stock_quantity'] ?? 1)),
            'brand'              => trim($data['brand'] ?? ''),
            'year'               => (int)($data['year'] ?? date('Y')),
            'transmission'       => $data['transmission'] ?? 'manual',
            'fuel_type'          => $data['fuel_type'] ?? 'bensin',
            'passenger_capacity' => max(1, (int)($data['passenger_capacity'] ?? 4)),
            'is_available'       => isset($data['is_available']) ? 1 : 0,
        ];
    }
}