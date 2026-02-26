<?php

namespace App\Controllers\Admin;

use App\Database;

class AuthController
{
    public function loginForm(): void
    {
        if (is_admin()) redirect('/admin/dashboard');

        $settings = settings();
        view('admin.auth.login', ['settings' => $settings]);
    }

    public function login(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token keamanan tidak valid');
            redirect('/admin/login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            flash('error', 'Username dan password harus diisi');
            redirect('/admin/login');
        }

        $db = Database::getInstance();
        $admin = $db->fetch("SELECT * FROM admin WHERE username = :username LIMIT 1", ['username' => $username]);

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            flash('error', 'Username atau password salah');
            redirect('/admin/login');
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id'       => $admin['id'],
            'username' => $admin['username'],
            'email'    => $admin['email'],
        ];

        flash('success', 'Selamat datang, ' . $admin['username'] . '!');
        redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        redirect('/admin/login');
    }
}