<?php
$primaryColor = settings()['primary_color'] ?? '#3b82f6';
ob_start();
?>

<!-- Hero Section -->
<section class="relative overflow-hidden py-20 sm:py-28"
         style="background: linear-gradient(135deg, <?= e($primaryColor) ?>15 0%, <?= e($primaryColor) ?>05 50%, white 100%)">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-10"
             style="background: <?= e($primaryColor) ?>"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full opacity-5"
             style="background: <?= e($primaryColor) ?>"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold mb-6"
                 style="background-color: <?= e($primaryColor) ?>15; color: <?= e($primaryColor) ?>">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: <?= e($primaryColor) ?>"></span>
                Tersedia sekarang
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                Rental Kendaraan<br>
                <span style="color: <?= e($primaryColor) ?>">Terpercaya & Mudah</span>
            </h1>

            <p class="text-lg text-gray-600 leading-relaxed mb-8">
                <?= e(settings()['tagline'] ?? 'Booking langsung via WhatsApp. Tanpa ribet, tanpa daftar akun. Pilih kendaraan impian Anda dan nikmati perjalanan nyaman.') ?>
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/kendaraan" class="btn-primary text-base py-4 px-8">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4"/>
                    </svg>
                    Lihat Kendaraan
                </a>
                <?php if (!empty(settings()['whatsapp_number'])): ?>
                <a href="https://wa.me/<?= e(settings()['whatsapp_number']) ?>" target="_blank"
                   class="btn-secondary text-base py-4 px-8">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Hubungi WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Featured Vehicles -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Kendaraan Tersedia</h2>
                <p class="text-gray-500 mt-1">Pilih kendaraan yang sesuai kebutuhan Anda</p>
            </div>
            <a href="/kendaraan" class="text-sm font-semibold hidden sm:block" style="color: <?= e($primaryColor) ?>">Lihat Semua →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($featuredVehicles as $v): ?>
            <div class="vehicle-card">
                <!-- Image -->
                <div class="relative h-48 overflow-hidden">
                    <?php if (!empty($v['primary_image'])): ?>
                        <img src="<?= e($v['primary_image']) ?>" alt="<?= e($v['vehicle_name']) ?>"
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m14-4V8a1 1 0 00-1-1h-1.172a1 1 0 00-.707.293L12.414 9"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm"
                              style="background-color: <?= e($primaryColor) ?>">
                            <?= ucfirst($v['vehicle_type']) ?>
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <h3 class="font-bold text-gray-900 text-lg mb-1"><?= e($v['vehicle_name']) ?></h3>
                    <p class="text-sm text-gray-500 mb-3">
                        <?= e($v['transmission']) === 'otomatis' ? 'AT' : 'MT' ?> ·
                        <?= e($v['passenger_capacity']) ?> penumpang
                        <?php if (!empty($v['year'])): ?> · <?= e($v['year']) ?><?php endif; ?>
                    </p>

                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Mulai dari</p>
                            <p class="text-xl font-extrabold" style="color: <?= e($primaryColor) ?>">
                                <?= format_rupiah($v['price_per_day']) ?>
                            </p>
                            <p class="text-xs text-gray-400">per hari</p>
                        </div>
                        <a href="/kendaraan/<?= e($v['slug']) ?>"
                           class="px-4 py-2 rounded-xl text-sm font-semibold text-white shadow-sm transition-all hover:shadow-md active:scale-95"
                           style="background-color: <?= e($primaryColor) ?>">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($featuredVehicles)): ?>
            <div class="col-span-3 text-center py-12 text-gray-400">
                Belum ada kendaraan tersedia
            </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-8 sm:hidden">
            <a href="/kendaraan" class="btn-primary">Lihat Semua Kendaraan</a>
        </div>
    </div>
</section>

<!-- How to Book -->
<section class="py-16" id="cara-booking">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Cara Booking Mudah</h2>
            <p class="text-gray-500">Proses booking cepat tanpa perlu daftar akun</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <?php foreach ([
                ['num' => '1', 'title' => 'Pilih Kendaraan', 'desc' => 'Browse koleksi kendaraan kami dan pilih yang sesuai kebutuhan dan budget Anda'],
                ['num' => '2', 'title' => 'Isi Form Booking', 'desc' => 'Lengkapi data diri dan tanggal rental. Sistem otomatis hitung total biaya'],
                ['num' => '3', 'title' => 'Chat WhatsApp', 'desc' => 'Lanjutkan konfirmasi via WhatsApp. Admin kami siap membantu 24 jam'],
            ] as $step): ?>
            <div class="relative text-center p-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-4 shadow-lg"
                     style="background-color: <?= e($primaryColor) ?>">
                    <?= $step['num'] ?>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2"><?= $step['title'] ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= $step['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_