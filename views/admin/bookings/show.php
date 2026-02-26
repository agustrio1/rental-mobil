<?php
/**
 * View: views/admin/bookings/show.php
 */

$pageTitle = 'Detail Booking #' . $booking['booking_code'];
ob_start();
$primaryColor = settings()['primary_color'] ?? '#3b82f6';
$csrfToken = csrf_token();

$statusConfig = [
    'pending'   => ['class' => 'badge-pending',    'label' => 'Pending',       'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    'confirmed' => ['class' => 'badge-confirmed',  'label' => 'Dikonfirmasi',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'ongoing'   => ['class' => 'bg-emerald-50 text-emerald-700 border border-emerald-100 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium', 'label' => 'Berjalan', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
    'completed' => ['class' => 'badge-completed',  'label' => 'Selesai',       'icon' => 'M5 13l4 4L19 7'],
    'cancelled' => ['class' => 'badge-cancelled',  'label' => 'Dibatalkan',    'icon' => 'M6 18L18 6M6 6l12 12'],
];
$currentStatus = $statusConfig[$booking['booking_status']] ?? $statusConfig['pending'];
?>

<!-- Header -->
<div class="mb-4 sm:mb-6 flex items-start justify-between gap-3">
    <div class="flex items-center gap-2 min-w-0">
        <a href="/admin/bookings" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors flex-shrink-0">
            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Detail Booking</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kode: <span class="font-mono font-semibold"><?= e($booking['booking_code']) ?></span></p>
        </div>
    </div>
    <span class="<?= $currentStatus['class'] ?> flex-shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $currentStatus['icon'] ?>"/>
        </svg>
        <?= $currentStatus['label'] ?>
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

    <!-- LEFT COLUMN -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Customer Info -->
        <div class="card p-4 sm:p-5">
            <h2 class="text-sm font-semibold text-gray-500 mb-3">DATA CUSTOMER</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nama</p>
                    <p class="font-semibold text-gray-900"><?= e($booking['customer_name']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">WhatsApp</p>
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-900"><?= e($booking['customer_phone']) ?></p>
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $booking['customer_phone']) ?>" target="_blank"
                           class="p-1 rounded hover:bg-green-50 text-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php if (!empty($booking['customer_email'])): ?>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Email</p>
                    <p class="font-semibold text-gray-900"><?= e($booking['customer_email']) ?></p>
                </div>
                <?php endif; ?>
                <?php if (!empty($booking['pickup_location'])): ?>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Lokasi Pengambilan</p>
                    <p class="font-semibold text-gray-900"><?= e($booking['pickup_location']) ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($booking['special_requests'])): ?>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400 mb-0.5">Catatan</p>
                <p class="text-sm text-gray-700"><?= nl2br(e($booking['special_requests'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Vehicle Info -->
        <div class="card p-4 sm:p-5">
            <h2 class="text-sm font-semibold text-gray-500 mb-3">KENDARAAN</h2>
            <div class="flex items-center gap-3">
                <?php if (!empty($booking['vehicle_image'])): ?>
                <img src="<?= e($booking['vehicle_image']) ?>" alt="<?= e($booking['vehicle_name']) ?>"
                     class="w-16 h-16 object-cover rounded-xl border border-gray-100 flex-shrink-0">
                <?php endif; ?>
                <div>
                    <p class="font-bold text-gray-900"><?= e($booking['vehicle_name']) ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <?= e($booking['vehicle_brand'] ?? '-') ?> • <?= e($booking['vehicle_year'] ?? '-') ?> • <?= ucfirst(e($booking['vehicle_type'] ?? '-')) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Rental Period -->
        <div class="card p-4 sm:p-5">
            <h2 class="text-sm font-semibold text-gray-500 mb-3">PERIODE RENTAL</h2>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Mulai</p>
                    <p class="font-semibold text-gray-900 text-sm"><?= format_date($booking['start_date'], 'd M Y') ?></p>
                    <p class="text-xs text-gray-400"><?= format_date($booking['start_date'], 'l') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Selesai</p>
                    <p class="font-semibold text-gray-900 text-sm"><?= format_date($booking['end_date'], 'd M Y') ?></p>
                    <p class="text-xs text-gray-400"><?= format_date($booking['end_date'], 'l') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Durasi</p>
                    <p class="font-semibold text-gray-900 text-sm"><?= $booking['duration_days'] ?> Hari</p>
                </div>
            </div>
        </div>

        <!-- Catatan Admin -->
        <?php if (!empty($booking['notes'])): ?>
        <div class="card p-4 sm:p-5">
            <h2 class="text-sm font-semibold text-gray-500 mb-2">CATATAN ADMIN</h2>
            <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-3"><?= nl2br(e($booking['notes'])) ?></p>
        </div>
        <?php endif; ?>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="lg:col-span-1">
        <div class="card p-4 sm:p-5 sticky top-4 sm:top-24 space-y-4">

            <!-- Harga -->
            <div>
                <h3 class="text-xs font-semibold text-gray-500 mb-3">RINCIAN HARGA</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Harga per hari</span>
                        <span class="font-semibold"><?= format_rupiah($booking['price_per_day']) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Durasi</span>
                        <span class="font-semibold"><?= $booking['duration_days'] ?> hari</span>
                    </div>
                    <div class="pt-2 border-t border-gray-200 flex justify-between items-center">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-xl" style="color: <?= e($primaryColor) ?>"><?= format_rupiah($booking['total_price']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-semibold text-gray-500 mb-3">UPDATE STATUS</h3>
                <form method="POST" action="/admin/bookings/<?= $booking['id'] ?>/update-status" class="space-y-3">
                    <input type="hidden" name="_csrf" value="<?= e($csrfToken) ?>">
                    <select name="status" class="input-field py-2.5 text-sm">
                        <option value="pending"   <?= $booking['booking_status'] === 'pending'   ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= $booking['booking_status'] === 'confirmed' ? 'selected' : '' ?>>Dikonfirmasi</option>
                        <option value="ongoing"   <?= $booking['booking_status'] === 'ongoing'   ? 'selected' : '' ?>>Berjalan</option>
                        <option value="completed" <?= $booking['booking_status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= $booking['booking_status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                    <textarea name="notes" rows="3" class="input-field py-2.5 text-sm resize-none"
                              placeholder="Catatan (opsional)..."><?= e($booking['notes'] ?? '') ?></textarea>
                    <button type="submit" class="w-full py-2.5 rounded-xl text-white font-medium text-sm hover:shadow-lg transition-all"
                            style="background-color: <?= e($primaryColor) ?>">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Info Booking -->
            <div class="pt-4 border-t border-gray-100 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-400">Dibuat</span>
                    <span class="font-medium text-gray-700"><?= format_date($booking['booking_date'], 'd M Y H:i') ?></span>
                </div>
                <?php if (!empty($booking['updated_at'])): ?>
                <div class="flex justify-between">
                    <span class="text-gray-400">Diupdate</span>
                    <span class="font-medium text-gray-700"><?= format_date($booking['updated_at'], 'd M Y H:i') ?></span>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
