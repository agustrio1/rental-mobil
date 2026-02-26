<?php
/**
 * View: views/public/vehicles/index.php
 * Controller: App\Controllers\Public\VehicleController::index()
 * Variables: $vehicles, $pagination, $filters, $settings, $seo
 */

ob_start();

$primaryColor = settings()['primary_color'] ?? '#3b82f6';
$currentType  = $filters['type']   ?? '';
$currentSearch = $filters['search'] ?? '';
?>

<!-- ═══════════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════════ -->
<section class="py-12 sm:py-16 border-b border-gray-100"
         style="background: linear-gradient(135deg, <?= e($primaryColor) ?>10 0%, white 60%)">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="max-w-xl">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3">
                Daftar Kendaraan
            </h1>
            <p class="text-gray-500 text-base">
                Temukan kendaraan yang sesuai kebutuhan dan budget Anda.
            </p>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════════
     FILTER & SEARCH
═══════════════════════════════════════════════ -->
<section class="py-6 bg-white border-b border-gray-100 sticky top-16 z-30 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <form method="GET" action="/kendaraan"
              class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

            <!-- Search -->
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Cari kendaraan..."
                       value="<?= e($currentSearch) ?>"
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:border-transparent"
                       style="--tw-ring-color: <?= e($primaryColor) ?>">
            </div>

            <!-- Type filter -->
            <div class="flex items-center gap-2 flex-wrap">
                <?php foreach ([
                    ''      => 'Semua',
                    'mobil' => 'Mobil',
                    'motor' => 'Motor',
                    'bus'   => 'Bus',
                    'truk'  => 'Truk',
                ] as $value => $label):
                    $isActive = ($currentType === $value);
                ?>
                <a href="?type=<?= $value ?><?= $currentSearch ? '&search='.urlencode($currentSearch) : '' ?>"
                   class="px-4 py-2 rounded-xl text-sm font-medium border transition-all <?= $isActive
                       ? 'text-white border-transparent shadow-sm'
                       : 'text-gray-600 border-gray-200 hover:border-gray-300 bg-white' ?>"
                   <?= $isActive ? 'style="background-color:'.e($primaryColor).'"' : '' ?>>
                    <?= $label ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Search button (mobile submit) -->
            <button type="submit"
                    class="sm:hidden px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                    style="background-color: <?= e($primaryColor) ?>">
                Cari
            </button>
        </form>
    </div>
</section>


<!-- ═══════════════════════════════════════════════
     VEHICLE GRID
═══════════════════════════════════════════════ -->
<section class="py-10 bg-gray-50 min-h-[60vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <!-- Result count -->
        <p class="text-sm text-gray-500 mb-6">
            Menampilkan <span class="font-semibold text-gray-800"><?= count($vehicles) ?></span>
            kendaraan<?= $currentSearch ? ' untuk "<strong>'.e($currentSearch).'</strong>"' : '' ?>
        </p>

        <?php if (!empty($vehicles)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($vehicles as $v): ?>
            <div class="vehicle-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">

                <!-- Image -->
                <div class="relative h-48 overflow-hidden bg-gray-100">
                    <?php if (!empty($v['primary_image'])): ?>
                        <img src="<?= e($v['primary_image']) ?>"
                             alt="<?= e($v['vehicle_name']) ?>"
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                             loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m14-4V8a1 1 0 00-1-1h-1.172"/>
                            </svg>
                        </div>
                    <?php endif; ?>

                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm"
                              style="background-color: <?= e($primaryColor) ?>">
                            <?= ucfirst(e($v['vehicle_type'])) ?>
                        </span>
                    </div>

                    <?php if (!empty($v['is_available'])): ?>
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold text-green-700 bg-green-100">
                            Tersedia
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <h2 class="font-bold text-gray-900 text-lg mb-1">
                        <?= e($v['vehicle_name']) ?>
                    </h2>
                    <p class="text-sm text-gray-500 mb-4">
                        <?= $v['transmission'] === 'otomatis' ? 'AT' : 'MT' ?> ·
                        <?= e($v['passenger_capacity'] ?? '-') ?> penumpang
                        <?php if (!empty($v['year'])): ?> · <?= e($v['year']) ?><?php endif; ?>
                        <?php if (!empty($v['brand'])): ?> · <?= e($v['brand']) ?><?php endif; ?>
                    </p>

                    <!-- Pricing -->
                    <div class="bg-gray-50 rounded-xl p-3 mb-4 grid grid-cols-3 gap-2 text-center text-xs">
                        <?php foreach ([
                            ['label' => 'Hari',   'price' => $v['price_per_day']],
                            ['label' => 'Minggu', 'price' => $v['price_per_week']],
                            ['label' => 'Bulan',  'price' => $v['price_per_month']],
                        ] as $p):
                            if (empty($p['price'])) continue;
                        ?>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">
                                <?= format_rupiah($p['price']) ?>
                            </div>
                            <div class="text-gray-400">/ <?= $p['label'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <a href="/kendaraan/<?= e($v['slug']) ?>"
                       class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:shadow-md active:scale-95"
                       style="background-color: <?= e($primaryColor) ?>">
                        Lihat Detail & Pesan
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (($pagination['last_page'] ?? 1) > 1): ?>
        <div class="flex items-center justify-center gap-2 mt-10">
            <?php
            $current  = $pagination['current_page'];
            $last     = $pagination['last_page'];
            $queryBase = http_build_query(array_filter([
                'type'   => $currentType,
                'search' => $currentSearch,
            ]));
            ?>

            <!-- Prev -->
            <?php if ($current > 1): ?>
            <a href="?<?= $queryBase ?>&page=<?= $current - 1 ?>"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 transition-colors">
                ← Prev
            </a>
            <?php endif; ?>

            <!-- Pages -->
            <?php for ($p = max(1, $current - 2); $p <= min($last, $current + 2); $p++): ?>
            <a href="?<?= $queryBase ?>&page=<?= $p ?>"
               class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium transition-all
                      <?= $p === $current ? 'text-white shadow-sm' : 'border border-gray-200 text-gray-600 hover:bg-gray-100' ?>"
               <?= $p === $current ? 'style="background-color:'.e($primaryColor).'"' : '' ?>>
                <?= $p ?>
            </a>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($current < $last): ?>
            <a href="?<?= $queryBase ?>&page=<?= $current + 1 ?>"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 transition-colors">
                Next →
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Empty state -->
        <div class="text-center py-20">
            <svg class="w-20 h-20 mx-auto mb-5 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Kendaraan tidak ditemukan</h3>
            <p class="text-gray-400 text-sm mb-6">Coba ubah filter atau kata kunci pencarian Anda.</p>
            <a href="/kendaraan"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white"
               style="background-color: <?= e($primaryColor) ?>">
                Reset Filter
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>


<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>