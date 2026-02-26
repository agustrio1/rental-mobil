<?php

// =============================================================================
// Global Helper Functions
// =============================================================================

if (!function_exists('view')) {
    /**
     * Render a view file.
     *
     * Resolves the views root in this order:
     *   1. APP_VIEWS_PATH environment / constant (set in bootstrap/index.php)
     *   2. <project-root>/resources/views
     *   3. <project-root>/views
     *   4. <src-dir>/views          (original behaviour)
     *
     * Usage:  view('public.home', ['key' => 'value'])
     * Looks for: <views-root>/public/home.php
     */
    function view(string $template, array $data = []): void
    {
        // ── resolve views root ─────────────────────────────────────────────────
        if (defined('APP_VIEWS_PATH') && is_dir(APP_VIEWS_PATH)) {
            $viewsRoot = rtrim(APP_VIEWS_PATH, '/\\');
        } else {
            // Walk up from src/ to project root
            // __DIR__         = src/Helpers
            // dirname once    = src/
            // dirname twice   = project root  ✓
            $projectRoot = dirname(__DIR__, 2);

            $candidates = [
                $projectRoot . '/views',              // project-root/views  ← struktur README
                $projectRoot . '/resources/views',    // project-root/resources/views
                dirname(__DIR__) . '/views',          // src/views (fallback)
            ];

            $viewsRoot = null;
            foreach ($candidates as $candidate) {
                if (is_dir($candidate)) {
                    $viewsRoot = realpath($candidate);
                    break;
                }
            }

            if ($viewsRoot === null) {
                throw new \RuntimeException(
                    "Views directory not found. Tried:\n  " . implode("\n  ", $candidates)
                );
            }
        }

        // ── build file path ────────────────────────────────────────────────────
        $relativePath = str_replace('.', DIRECTORY_SEPARATOR, $template) . '.php';
        $viewPath     = $viewsRoot . DIRECTORY_SEPARATOR . $relativePath;

        if (!file_exists($viewPath)) {
            throw new \RuntimeException(
                "View not found: [{$template}]\n" .
                "  Looked in: {$viewPath}\n" .
                "  Views root: {$viewsRoot}"
            );
        }

        // ── render ─────────────────────────────────────────────────────────────
        extract($data, EXTR_SKIP);
        require $viewPath;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('back')) {
    function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referer);
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        return rtrim($config['url'], '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML special characters
     */
    function e(?string $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     */
    function old(string $key, mixed $default = ''): string
    {
        return e($_SESSION['_old'][$key] ?? $default);
    }
}

if (!function_exists('flash')) {
    /**
     * Set flash message
     */
    function flash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['_flash'][$type] = $message;
    }
}

if (!function_exists('get_flash')) {
    /**
     * Get and clear flash message
     */
    function get_flash(string $type): ?string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $msg = $_SESSION['_flash'][$type] ?? null;
        unset($_SESSION['_flash'][$type]);
        return $msg;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return hash_equals($_SESSION['_csrf'] ?? '', $token);
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date')) {
    function format_date(string $date, string $format = 'd F Y'): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $ts     = strtotime($date);
        $result = date($format, $ts);

        foreach ($bulan as $num => $name) {
            $result = str_replace(date('F', mktime(0, 0, 0, $num, 1)), $name, $result);
        }

        return $result;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        return trim($text, '-');
    }
}

if (!function_exists('generate_booking_code')) {
    function generate_booking_code(): string
    {
        return 'RNT-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }
}

if (!function_exists('json_response')) {
    function json_response(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!function_exists('upload_file')) {
    function upload_file(
        array  $file,
        string $directory,
        array  $allowedTypes = ['image/jpeg', 'image/png', 'image/webp']
    ): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        if (!in_array($file['type'], $allowedTypes)) return null;

        $config     = require dirname(__DIR__, 2) . '/config/app.php';
        $uploadPath = $config['upload']['path'] . '/' . $directory;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $fullPath = $uploadPath . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return $config['upload']['url_path'] . '/' . $directory . '/' . $filename;
        }

        return null;
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['admin'] ?? null;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(): bool
    {
        return auth() !== null;
    }
}

if (!function_exists('settings')) {
    /**
     * Get website settings (cached per request)
     */
    function settings(): array
    {
        static $cache = null;
        if ($cache !== null) return $cache;

        $db    = \App\Database\Connection::getInstance();
        $stmt  = $db->query(
            "SELECT ws.*, l.logo_path, l.favicon_path
             FROM website_settings ws
             LEFT JOIN logo l ON l.setting_id = ws.id AND l.is_active = 1
             LIMIT 1"
        );
        $cache = $stmt->fetch() ?: [];
        return $cache;
    }
}

if (!function_exists('active_route')) {
    /**
     * Return 'active' class if current URL matches
     */
    function active_route(string $path): string
    {
        $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $current === $path ? 'active' : '';
    }
}

if (!function_exists('truncate')) {
    function truncate(string $text, int $length = 100): string
    {
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . '...';
    }
}
