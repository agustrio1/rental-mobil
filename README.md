# 🚗 Website Rental Kendaraan - PHP Native

## Stack Teknologi
- **Backend**: PHP 8.1+ Native (tanpa framework)
- **Database**: MySQL 8.0+
- **CSS Framework**: Tailwind CSS v4 (via CLI)
- **Admin UI**: TailAdmin
- **JS**: Alpine.js + HTMX (via npm/CLI)
- **Dependency Manager**: Composer (PHP) + npm (JS/CSS)

## Cara Install

### 1. Clone / Download project ini

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies (Tailwind CSS v4, Alpine.js, HTMX)
```bash
npm install
```

### 4. Build CSS (Tailwind v4)
```bash
# Development (watch mode)
npm run dev

# Production
npm run build
```

### 5. Setup Environment
```bash
cp .env.example .env
# Edit .env sesuai konfigurasi database Anda
```

### 6. Import Database
```bash
mysql -u root -p rental_db < database/schema.sql
```

### 7. Jalankan dengan PHP built-in server
```bash
php -S localhost:8000 -t public
```

Atau gunakan Apache/Nginx dengan document root ke folder `public/`

## Struktur Folder

```
rental-app/
├── public/              # Document root (web server)
│   ├── index.php        # Entry point
│   ├── css/
│   │   └── app.css      # Compiled Tailwind CSS
│   ├── js/
│   │   └── app.js       # Alpine.js + HTMX bundle
│   └── uploads/         # File uploads (logo, bukti bayar, dll)
├── src/
│   ├── Controllers/
│   │   ├── Admin/       # Admin controllers
│   │   └── Public/      # Public controllers
│   ├── Models/          # Database models
│   ├── Middleware/      # Auth middleware
│   └── Helpers/         # Helper functions
├── views/
│   ├── admin/           # Admin views
│   └── public/          # Public views
├── config/              # Konfigurasi app
├── database/
│   └── schema.sql       # Database schema
├── routes/
│   └── web.php          # Route definitions
├── tailwind.config.js   # Tailwind v4 config
├── input.css            # Tailwind input CSS
├── composer.json
├── package.json
└── .env.example
```

## Akun Admin Default
- **URL**: http://localhost:8000/admin
- **Username**: admin
- **Password**: admin123

## Fitur
- ✅ Manajemen kendaraan (CRUD)
- ✅ Multi gambar per kendaraan
- ✅ Booking via WhatsApp (tanpa login user)
- ✅ Konfirmasi pembayaran
- ✅ Dashboard admin
- ✅ Pengaturan website (logo, tema, SEO)
- ✅ Warna tema bisa diubah dari settings
- ✅ Sitemap.xml otomatis
- ✅ Meta tags SEO per halaman