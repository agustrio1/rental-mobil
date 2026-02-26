<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $s = settings();
    $primaryColor = $s['primary_color'] ?? '#3b82f6';
    $palette = \App\Models\Setting::generateColorPalette($primaryColor);
    ?>

    <!-- SEO Meta Tags -->
    <title><?= e($seo['meta_title'] ?? $s['meta_title'] ?? $s['site_name'] ?? 'Rental Kendaraan') ?></title>
    <meta name="description" content="<?= e($seo['meta_description'] ?? $s['meta_description'] ?? '') ?>">
    <?php if (!empty($seo['meta_keywords'] ?? $s['meta_keywords'])): ?>
    <meta name="keywords" content="<?= e($seo['meta_keywords'] ?? $s['meta_keywords']) ?>">
    <?php endif; ?>
    <?php if (!empty($s['canonical_url'])): ?>
    <link rel="canonical" href="<?= e($s['canonical_url']) ?>">
    <?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($seo['meta_title'] ?? $s['site_name'] ?? '') ?>">
    <meta property="og:description" content="<?= e($seo['meta_description'] ?? '') ?>">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <?php if (!empty($s['favicon_path'])): ?>
    <link rel="icon" type="image/x-icon" href="<?= e($s['favicon_path']) ?>">
    <?php endif; ?>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- Dynamic Theme CSS -->
    <style>
        :root {
            <?php foreach ($palette as $shade => $hex): ?>
            --color-primary-<?= $shade ?>: <?= $hex ?>;
            --theme-primary-<?= $shade ?>: <?= $hex ?>;
            <?php endforeach; ?>
            --theme-primary-color: <?= $primaryColor ?>;
        }
        .btn-primary { background-color: <?= $primaryColor ?>; }
        .btn-primary:hover { filter: brightness(0.9); }
        .text-primary { color: <?= $primaryColor ?>; }
        .bg-primary { background-color: <?= $primaryColor ?>; }
        .border-primary { border-color: <?= $primaryColor ?>; }
        .ring-primary { --tw-ring-color: <?= $primaryColor ?>; }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Sitemap -->
    <link rel="sitemap" type="application/xml" href="/sitemap.xml">

    <!-- Google Analytics (GA4) -->
    <?php if (!empty($s['google_analytics_id'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($s['google_analytics_id']) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', '<?= e($s['google_analytics_id']) ?>');
    </script>
    <?php endif; ?>

    <!-- Facebook Pixel -->
    <?php if (!empty($s['facebook_pixel_id'])): ?>
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= e($s['facebook_pixel_id']) ?>');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
             src="https://www.facebook.com/tr?id=<?= e($s['facebook_pixel_id']) ?>&ev=PageView&noscript=1"/>
    </noscript>
    <?php endif; ?>

</head>

<body class="font-sans text-gray-800 bg-white">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm"
         x-data="{ open: false }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <?php if (!empty($s['logo_path'])): ?>
                        <img src="<?= e($s['logo_path']) ?>" alt="<?= e($s['site_name']) ?>" class="h-8 w-auto">
                    <?php else: ?>
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold"
                             style="background-color: <?= e($primaryColor) ?>">
                            R
                        </div>
                    <?php endif; ?>
                    <span class="font-bold text-gray-900 text-lg"><?= e($s['site_name'] ?? 'Rental') ?></span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm font-medium transition-colors <?= active_route('/') === 'active' ? 'text-primary' : 'text-gray-600 hover:text-gray-900' ?>"
                       style="<?= active_route('/') === 'active' ? 'color: ' . $primaryColor : '' ?>">Beranda</a>
                    <a href="/kendaraan" class="text-sm font-medium transition-colors <?= str_starts_with($_SERVER['REQUEST_URI'], '/kendaraan') ? '' : 'text-gray-600 hover:text-gray-900' ?>"
                       style="<?= str_starts_with($_SERVER['REQUEST_URI'], '/kendaraan') ? 'color: ' . $primaryColor : '' ?>">Kendaraan</a>
                    <a href="#cara-booking" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Cara Booking</a>
                    <a href="/kontak" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Kontak</a>
                </div>

                <!-- CTA + Mobile menu -->
                <div class="flex items-center gap-3">
                    <?php if (!empty($s['whatsapp_number'])): ?>
                    <a href="https://wa.me/<?= e($s['whatsapp_number']) ?>" target="_blank"
                       class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-sm"
                       style="background-color: #25d366">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                    <?php endif; ?>

                    <!-- Mobile hamburger -->
                    <button @click="open = !open" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="open" x-transition class="md:hidden border-t border-gray-100 py-4 space-y-2">
                <a href="/" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Beranda</a>
                <a href="/kendaraan" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Kendaraan</a>
                <a href="#cara-booking" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Cara Booking</a>
                <a href="/kontak" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">Kontak</a>
                <?php if (!empty($s['whatsapp_number'])): ?>
                <a href="https://wa.me/<?= e($s['whatsapp_number']) ?>" target="_blank"
                   class="block px-3 py-2 rounded-lg text-sm font-semibold text-white mt-2"
                   style="background-color: #25d366">
                    Chat WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white" id="kontak">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <?php if (!empty($s['logo_path'])): ?>
                            <img src="<?= e($s['logo_path']) ?>" alt="" class="h-8 brightness-0 invert">
                        <?php else: ?>
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold" style="background-color: <?= e($primaryColor) ?>">R</div>
                        <?php endif; ?>
                        <span class="font-bold text-lg"><?= e($s['site_name'] ?? '') ?></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        <?= e($s['tagline'] ?? 'Solusi transportasi terpercaya untuk perjalanan Anda.') ?>
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-400">Navigasi</h4>
                    <div class="space-y-2">
                        <a href="/" class="block text-sm text-gray-400 hover:text-white transition-colors">Beranda</a>
                        <a href="/kendaraan" class="block text-sm text-gray-400 hover:text-white transition-colors">Daftar Kendaraan</a>
                        <a href="/sitemap.xml" class="block text-sm text-gray-400 hover:text-white transition-colors">Sitemap</a>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-400">Kontak</h4>
                    <div class="space-y-3">
                        <?php if (!empty($s['whatsapp_number'])): ?>
                        <a href="https://wa.me/<?= e($s['whatsapp_number']) ?>" target="_blank"
                           class="flex items-center gap-3 text-sm text-gray-400 hover:text-white transition-colors">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-500/10">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            +<?= e($s['whatsapp_number']) ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($s['email'])): ?>
                        <div class="flex items-center gap-3 text-sm text-gray-400">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: <?= e($primaryColor) ?>20">
                                <svg class="w-4 h-4" style="color: <?= e($primaryColor) ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <?= e($s['email']) ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($s['address'])): ?>
                        <div class="flex items-start gap-3 text-sm text-gray-400">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color: <?= e($primaryColor) ?>20">
                                <svg class="w-4 h-4" style="color: <?= e($primaryColor) ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </div>
                            <?= e($s['address']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                <?= e($s['footer_text'] ?? '© ' . date('Y') . ' ' . ($s['site_name'] ?? 'Rental Kendaraan') . '. All rights reserved.') ?>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script src="/js/alpine.min.js" defer></script>
    <!-- HTMX -->
    <script src="/js/htmx.min.js"></script>
    <!-- App JS -->
    <script src="/js/app.js"></script>
    <?php if (!empty($headScript)) echo $headScript; ?>

</body>
</html>
