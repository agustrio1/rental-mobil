<?php

use App\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\Admin;
use App\Controllers\Public as Pub;

$router = new Router();

// =============================================================================
// PUBLIC ROUTES
// =============================================================================

$router->get('/', [Pub\HomeController::class, 'index']);
$router->get('/kontak', [Pub\ContactController::class, 'index']);
$router->get('/sitemap.xml', [Pub\HomeController::class, 'sitemap']);

// Vehicles
$router->get('/kendaraan', [Pub\VehicleController::class, 'index']);
$router->get('/kendaraan/{slug}', [Pub\VehicleController::class, 'show']);
$router->post('/api/check-availability', [Pub\VehicleController::class, 'checkAvailability']);

// Booking
$router->post('/booking/store', [Pub\BookingController::class, 'store']);
$router->get('/booking/konfirmasi', [Pub\BookingController::class, 'confirm']);
$router->post('/booking/konfirmasi/submit', [Pub\BookingController::class, 'submitPayment']);

// =============================================================================
// ADMIN ROUTES
// =============================================================================

// Auth (no middleware)
$router->get('/admin/login', [Admin\AuthController::class, 'loginForm']);
$router->post('/admin/login', [Admin\AuthController::class, 'login']);
$router->get('/admin/logout', [Admin\AuthController::class, 'logout']);

// Protected admin routes
$router->group('/admin', function (Router $r) {
    $r->get('', function () { redirect('/admin/dashboard'); });
    $r->get('/dashboard', [Admin\DashboardController::class, 'index']);

    // Vehicles
    $r->get('/vehicles', [Admin\VehicleController::class, 'index']);
    $r->get('/vehicles/create', [Admin\VehicleController::class, 'create']);
    $r->post('/vehicles/store', [Admin\VehicleController::class, 'store']);
    $r->get('/vehicles/{id}/edit', [Admin\VehicleController::class, 'edit']);
    $r->post('/vehicles/{id}/update', [Admin\VehicleController::class, 'update']);
    $r->post('/vehicles/{id}/delete', [Admin\VehicleController::class, 'delete']);
    $r->post('/vehicles/{id}/toggle', [Admin\VehicleController::class, 'toggleAvailability']);
    $r->post('/vehicles/images/{id}/delete', [Admin\VehicleController::class, 'deleteImage']);

    // Bookings
    $r->get('/bookings', [Admin\BookingController::class, 'index']);
    $r->get('/bookings/{id}', [Admin\BookingController::class, 'show']);
    $r->post('/bookings/{id}/update-status', [Admin\BookingController::class, 'updateStatus']);
    $r->post('/bookings/{id}/verify-payment', [Admin\BookingController::class, 'verifyPayment']);

    // Settings
    $r->get('/settings', [Admin\SettingController::class, 'index']);
    $r->post('/settings/general', [Admin\SettingController::class, 'updateGeneral']);
    $r->post('/settings/seo', [Admin\SettingController::class, 'updateSeo']);

}, [AuthMiddleware::class]);

return $router;