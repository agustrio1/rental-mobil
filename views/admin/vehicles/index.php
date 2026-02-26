<?php
$pageTitle = 'Manajemen Kendaraan';
ob_start();
$primaryColor = settings()['primary_color'] ?? '#3b82f6';
?>

<!-- Actions Bar -->
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex gap-2 flex-1 max-w-md">
        <input type="text" name="search" value="<?= e($filters['search']) ?>"
               class="input-field py-2 text-sm" placeholder="Cari nama atau merek...">
        <select name="type" class="input-field py-2 text-sm w-32">
            <option value="">Semua Tipe</option>
            <option value="mobil" <?= $filters['type'] === 'mobil' ? 'selected' : '' ?>>Mobil</option>
            <option value="motor" <?= $filters['type'] === 'motor' ? 'selected' : '' ?>>Motor</option>
            <option value="bus" <?= $filters['type'] === 'bus' ? 'selected' : '' ?>>Bus</option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-xl text-white text-sm font-medium" style="background-color: <?= e($primaryColor) ?>">Cari</button>
    </form>

    <a href="/admin/vehicles/create" class="px-4 py-2.5 rounded-xl text-white text-sm font-medium flex items-center gap-2 shrink-0 hover:shadow-lg hover:opacity-90 transition-all" style="background-color: <?= e($primaryColor) ?>">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kendaraan
    </a>
</div>

<!-- Vehicle Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header">Kendaraan</th>
                    <th class="table-header">Tipe</th>
                    <th class="table-header">Harga/Hari</th>
                    <th class="table-header">Stok</th>
                    <th class="table-header">Status</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $v): ?>
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($v['primary_image'])): ?>
                                <img src="<?= e($v['primary_image']) ?>" alt="" class="w-12 h-10 rounded-lg object-cover border border-gray-100">
                            <?php else: ?>
                                <div class="w-12 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-medium text-gray-800 text-sm"><?= e($v['vehicle_name']) ?></p>
                                <p class="text-xs text-gray-400"><?= e($v['brand'] ?? '-') ?> <?= e($v['year'] ?? '') ?> · <?= e($v['transmission']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            <?= $v['vehicle_type'] === 'mobil' ? 'bg-blue-50 text-blue-700' : ($v['vehicle_type'] === 'motor' ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-600') ?>">
                            <?= ucfirst($v['vehicle_type']) ?>
                        </span>
                    </td>
                    <td class="table-cell font-medium text-gray-800">
                        <?= format_rupiah($v['price_per_day']) ?>
                    </td>
                    <td class="table-cell text-center"><?= $v['stock_quantity'] ?></td>
                    <td class="table-cell">
                        <button
                            hx-post="/admin/vehicles/<?= $v['id'] ?>/toggle"
                            hx-swap="none"
                            onclick="this.parentElement.querySelector('span').classList.toggle('bg-green-50');
                                     this.parentElement.querySelector('span').classList.toggle('text-green-700');
                                     this.parentElement.querySelector('span').classList.toggle('bg-gray-100');
                                     this.parentElement.querySelector('span').classList.toggle('text-gray-500');"
                            class="cursor-pointer"
                            hx-headers='{"X-CSRF-Token": "<?= csrf_token() ?>"}'>
                            <span class="<?= $v['is_available'] ? 'badge-available' : 'badge-unavailable' ?>">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                <?= $v['is_available'] ? 'Tersedia' : 'Tidak Tersedia' ?>
                            </span>
                        </button>
                    </td>
                    <td class="table-cell">
                        <div class="flex items-center gap-2">
                            <a href="/admin/vehicles/<?= $v['id'] ?>/edit"
                               class="p-1.5 rounded-lg hover:bg-blue-50 transition-colors" style="color: <?= e($primaryColor) ?>" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="/admin/vehicles/<?= $v['id'] ?>/delete" class="inline"
                                  x-data onsubmit="return confirm('Hapus kendaraan <?= e(addslashes($v['vehicle_name'])) ?>?')">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($vehicles)): ?>
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0"/>
                        </svg>
                        <p class="text-gray-400">Belum ada kendaraan. <a href="/admin/vehicles/create" style="color:<?= e($primaryColor) ?>">Tambah sekarang</a></p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['last_page'] > 1): ?>
    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between text-sm">
        <p class="text-gray-500">
            Menampilkan <?= $pagination['from'] ?>-<?= $pagination['to'] ?> dari <?= $pagination['total'] ?> kendaraan
        </p>
        <div class="flex gap-1">
            <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
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