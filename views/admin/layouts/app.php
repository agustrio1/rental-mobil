<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($pageTitle ?? 'Dashboard') ?> | Admin <?= e(settings()['site_name'] ?? 'Rental') ?></title>

    <!-- Tailwind CSS (compiled) -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- Dynamic Theme CSS dari settings -->
    <?php
    $s = settings();
    $primaryColor = $s['primary_color'] ?? '#3b82f6';
    $palette = \App\Models\Setting::generateColorPalette($primaryColor);
    ?>
    <style>
        :root {
            <?php foreach ($palette as $shade => $hex): ?>
            --color-primary-<?= $shade ?>: <?= $hex ?>;
            --theme-primary-<?= $shade ?>: <?= $hex ?>;
            <?php endforeach; ?>
        }

        /* TailAdmin-inspired admin theme */
        :root {
            --sidebar-bg: #1c2434;
            --sidebar-hover: #333a4f;
            --sidebar-active: <?= $primaryColor ?>;
        }
    </style>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php if (isset($extraHead)): ?>
        <?= $extraHead ?>
    <?php endif; ?>
</head>

<body class="bg-gray-50 font-sans" x-data="adminLayout()">

    <!-- Sidebar Overlay (mobile) -->
    <div x-show="sidebarOpen && window.innerWidth < 1024"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-in duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-out duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"
         style="display:none">
    </div>

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-full w-72 z-50 transition-transform duration-300"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           style="background-color: var(--sidebar-bg)">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <?php $logo = settings()['logo_path'] ?? null; ?>
            <?php if ($logo): ?>
                <img src="<?= e($logo) ?>" alt="Logo" class="h-8 w-auto">
            <?php else: ?>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                     style="background-color: <?= e($primaryColor) ?>">
                    R
                </div>
            <?php endif; ?>
            <div>
                <h1 class="text-white font-bold text-base leading-tight"><?= e(settings()['site_name'] ?? 'Rental') ?></h1>
                <p class="text-white/50 text-xs">Admin Panel</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="px-4 py-5 space-y-1">
            <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Menu Utama</p>

            <a href="/admin/dashboard"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                      <?= active_route('/admin/dashboard') === 'active' ? 'text-white' : 'text-white/70 hover:text-white hover:bg-white/10' ?>"
               <?php if (active_route('/admin/dashboard') === 'active'): ?>
               style="background-color: <?= e($primaryColor) ?>"
               <?php endif; ?>>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="/admin/vehicles"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                      <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/vehicles') ? 'text-white' : 'text-white/70 hover:text-white hover:bg-white/10' ?>"
               <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/vehicles')): ?>
               style="background-color: <?= e($primaryColor) ?>"
               <?php endif; ?>>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m14-4V8a1 1 0 00-1-1h-1.172a1 1 0 00-.707.293L12.414 9l-.707.707A1 1 0 0011 10H5"/>
                </svg>
                Kendaraan
            </a>

            <a href="/admin/bookings"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                      <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/bookings') ? 'text-white' : 'text-white/70 hover:text-white hover:bg-white/10' ?>"
               <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/bookings')): ?>
               style="background-color: <?= e($primaryColor) ?>"
               <?php endif; ?>>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Booking
                <?php
                $pendingCount = \App\Database::getInstance()->count("SELECT COUNT(*) FROM bookings WHERE booking_status = 'pending'");
                if ($pendingCount > 0):
                ?>
                <span class="ml-auto text-xs bg-red-500 text-white rounded-full px-2 py-0.5"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>

            <div class="pt-4">
                <p class="text-white/30 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Pengaturan</p>
                <a href="/admin/settings"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                          <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/settings') ? 'text-white' : 'text-white/70 hover:text-white hover:bg-white/10' ?>"
                   <?php if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/settings')): ?>
                   style="background-color: <?= e($primaryColor) ?>"
                   <?php endif; ?>>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pengaturan Website
                </a>
            </div>

            <!-- Visit website -->
            <div class="pt-2">
                <a href="/" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white/50 hover:text-white/80 hover:bg-white/5 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Website
                </a>
            </div>
        </nav>

        <!-- User info -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-semibold text-sm text-white"
                     style="background-color: <?= e($primaryColor) ?>">
                    <?= strtoupper(substr(auth()['username'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate"><?= e(auth()['username'] ?? '') ?></p>
                    <p class="text-white/40 text-xs truncate"><?= e(auth()['email'] ?? '') ?></p>
                </div>
                <a href="/admin/logout" title="Logout"
                   class="text-white/40 hover:text-red-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-72 min-h-screen flex flex-col">
        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-4 sm:px-6 py-4">
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button @click="toggleSidebar()"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800"><?= e($pageTitle ?? 'Dashboard') ?></h2>
                    <?php if (isset($breadcrumb)): ?>
                    <p class="text-sm text-gray-500"><?= e($breadcrumb) ?></p>
                    <?php endif; ?>
                </div>

                <div class="ml-auto flex items-center gap-3">
                    <!-- Quick link to website -->
                    <a href="/" target="_blank"
                       class="hidden sm:flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Website
                    </a>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php $success = get_flash('success'); $error = get_flash('error'); ?>
        <?php if ($success || $error): ?>
        <div class="px-4 sm:px-6 pt-4">
            <?php if ($success): ?>
            <div x-data="flashMessage()" x-show="show" x-transition:leave="transition-opacity duration-300"
                 class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm animate-fade-in">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= e($success) ?>
            </div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div x-data="flashMessage()" x-show="show" x-transition:leave="transition-opacity duration-300"
                 class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm animate-fade-in">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= e($error) ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="flex-1 px-4 sm:px-6 py-6">
            <?= $content ?? '' ?>
        </main>

        <footer class="px-6 py-4 border-t border-gray-100 text-center text-xs text-gray-400">
            <?= e(settings()['site_name'] ?? 'Rental') ?> Admin Panel &copy; <?= date('Y') ?>
        </footer>
    </div>

    <!-- Alpine.js -->
    <script src="/js/alpine.min.js" defer></script>
    <!-- HTMX -->
    <script src="/js/htmx.min.js"></script>
    <!-- App JS -->
    <script src="/js/app.js"></script>

    <?php if (isset($extraScripts)): ?>
        <?= $extraScripts ?>
    <?php endif; ?>
</body>
</html>