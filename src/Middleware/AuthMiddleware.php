<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin'])) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('/admin/login');
        }
    }
}