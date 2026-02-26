<?php
$pageTitle = 'Manajemen Booking';
ob_start();
$primaryColor = settings()['primary_color'] ?? '#3b82f6';
?>

<!-- Filters -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <form method="GET" class="flex gap-2 flex-1">
        <input type="text" name="search" value="<?= e($filters['search']) ?>"
               class="input-field py-2 text-sm" placeholder="Cari kode, nama, atau HP...">
        <select name="status" class="input-field py-2 text-sm w-40">
            <option value="">Semua Status</option>
            <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="confirmed" <?= $filters['status'] === 'confirmed' ? 'selected' : '' ?>>Dikonfirmasi</option>
            <option value="ongoing" <?= $filters['status'] === 'ongoing' ? 'selected' : '' ?>>Berjalan</option>
            <option value="completed" <?= $filters['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
            <option value="cancelled" <?= $filters['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-xl text-white text-sm font-medium shrink-0" style="background-color: <?= e($primaryColor) ?>">Filter</button>
    </form>
</div>

<!-- Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header">Kode Booking</th>
                    <th class="table-header">Customer</th>
                    <th class="table-header">Kendaraan</th>
                    <th class="table-header">Tanggal Rental</th>
                    <th class="table-header">Total</th>
                    <th class="table-header">Status</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr class="table-row">
                    <td class="table-cell">
                        <span class="font-mono text-xs font-semibold text-gray-700"><?= e($b['booking_code']) ?></span>
                        <p class="text-xs text-gray-400 mt-0.5"><?= format_date($b['booking_date'], 'd M Y H:i') ?></p>
                    </td>
                    <td class="table-cell">
                        <p class="font-medium text-gray-800 text-sm"><?= e($b['customer_name']) ?></p>
                        <p class="text-xs text-gray-400"><?= e($b['customer_phone']) ?></p>
                    </td>
                    <td class="table-cell text-sm"><?= e($b['vehicle_name']) ?></td>
                    <td class="table-cell text-xs">
                        <p><?= format_date($b['start_date'], 'd M Y') ?></p>
                        <p class="text-gray-400">s/d <?= format_date($b['end_date'], 'd M Y') ?></p>
                        <p class="text-gray-400"><?= $b['duration_days'] ?> hari</p>
                    </td>
                    <td class="table-cell font-semibold text-gray-800"><?= format_rupiah($b['total_price']) ?></td>
                    <td class="table-cell">
                        <?php
                        $statusConfig = [
                            'pending'   => ['class' => 'badge-pending', 'label' => 'Pending'],
                            'confirmed' => ['class' => 'badge-confirmed', 'label' => 'Dikonfirmasi'],
                            'ongoing'   => ['class' => 'bg-emerald-50 text-emerald-700 border border-emerald-100 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium', 'label' => 'Berjalan'],
                            'completed' => ['class' => 'badge-completed', 'label' => 'Selesai'],
                            'cancelled' => ['class' => 'badge-cancelled', 'label' => 'Dibatalkan'],
                        ];
                        $sc = $statusConfig[$b['booking_status']] ?? ['class' => 'badge-pending', 'label' => $b['booking_status']];
                        ?>
                        <span class="<?= $sc['class'] ?>">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <?= $sc['label'] ?>
                        </span>
                        <?php if (!empty($b['payment_status'])): ?>
                        <p class="text-xs text-gray-400 mt-1">
                            Bayar: <?= $b['payment_status'] === 'verified' ? '✅' : ($b['payment_status'] === 'rejected' ? '❌' : '⏳') ?>
                        </p>
                        <?php endif; ?>
                    </td>
                    <td class="table-cell">
                        <a href="/admin/bookings/<?= $b['id'] ?>"
                           class="p-1.5 rounded-lg hover:bg-blue-50 inline-flex" style="color: <?= e($primaryColor) ?>" title="Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $b['customer_phone']) ?>"
                           target="_blank"
                           class="p-1.5 rounded-lg hover:bg-green-50 text-green-600 inline-flex" title="WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">Belum ada booking</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['last_page'] > 1): ?>
    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm">
        <p class="text-gray-500">
            Menampilkan <?= $pagination['from'] ?>-<?= $pagination['to'] ?> dari <?= $pagination['total'] ?> booking
        </p>
        <div class="flex gap-1">
            <?php for ($i = 1; $i <= min($pagination['last_page'], 10); $i++): ?>
            <a href="?page=<?= $i ?>&<?= http_build_query(array_diff_key($filters, ['page' => ''])) ?>"
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?= $i === $pagination['current_page'] ? 'text-white' : 'text-gray-600 hover:bg-gray-100' ?>"
               <?= $i === $pagination['current_page'] ? "style=\"background-color: " . e($primaryColor) . "\"" : '' ?>>
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>