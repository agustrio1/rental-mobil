<?php

namespace App\Controllers\Public;

use App\Models\Vehicle;
use App\Models\Setting;

class HomeController
{
    public function index(): void
    {
        $vehicle = new Vehicle();
        $featuredVehicles = $vehicle->all(['available' => true], 1, 6)['data'];
        $settings = settings();
        $seo = (new Setting())->getSeoBySlug('home');

        view('public.home.index', compact('featuredVehicles', 'settings', 'seo'));
    }

    public function sitemap(): void
    {
        $vehicle = new Vehicle();
        $vehicles = $vehicle->all([], 0)['data'];
        $settings = settings();

        header('Content-Type: application/xml; charset=utf-8');
        view('public.sitemap', compact('vehicles', 'settings'));
    }
}