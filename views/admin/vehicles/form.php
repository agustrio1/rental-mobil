<?php
$isEdit = !empty($vehicle);
$pageTitle = $isEdit ? 'Edit Kendaraan' : 'Tambah Kendaraan';
$primaryColor = settings()['primary_color'] ?? '#3b82f6';
ob_start();
$action = $isEdit ? "/admin/vehicles/{$vehicle['id']}/update" : '/admin/vehicles/store';
?>

<div class="max-w-4xl">
    <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Info Dasar -->
            <div class="card p-6 lg:col-span-2">
                <h3 class="font-semibold text-gray-800 mb-5">Informasi Kendaraan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nama Kendaraan *</label>
                        <input type="text" name="vehicle_name"
                               value="<?= e($vehicle['vehicle_name'] ?? '') ?>"
                               class="input-field" required placeholder="Contoh: Toyota Avanza 2022">
                    </div>
                    <div>
                        <label class="label">Merek</label>
                        <input type="text" name="brand"
                               value="<?= e($vehicle['brand'] ?? '') ?>"
                               class="input-field" placeholder="Toyota, Honda, Yamaha...">
                    </div>
                    <div>
                        <label class="label">Tipe Kendaraan *</label>
                        <select name="vehicle_type" class="input-field" required>
                            <option value="mobil" <?= ($vehicle['vehicle_type'] ?? '') === 'mobil' ? 'selected' : '' ?>>Mobil</option>
                            <option value="motor" <?= ($vehicle['vehicle_type'] ?? '') === 'motor' ? 'selected' : '' ?>>Motor</option>
                            <option value="bus" <?= ($vehicle['vehicle_type'] ?? '') === 'bus' ? 'selected' : '' ?>>Bus</option>
                            <option value="truk" <?= ($vehicle['vehicle_type'] ?? '') === 'truk' ? 'selected' : '' ?>>Truk</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Tahun</label>
                        <input type="number" name="year"
                               value="<?= e($vehicle['year'] ?? date('Y')) ?>"
                               class="input-field" min="1990" max="<?= date('Y') + 1 ?>">
                    </div>
                    <div>
                        <label class="label">Transmisi</label>
                        <select name="transmission" class="input-field">
                            <option value="manual" <?= ($vehicle['transmission'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
                            <option value="otomatis" <?= ($vehicle['transmission'] ?? '') === 'otomatis' ? 'selected' : '' ?>>Otomatis</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Bahan Bakar</label>
                        <select name="fuel_type" class="input-field">
                            <option value="bensin" <?= ($vehicle['fuel_type'] ?? '') === 'bensin' ? 'selected' : '' ?>>Bensin</option>
                            <option value="solar" <?= ($vehicle['fuel_type'] ?? '') === 'solar' ? 'selected' : '' ?>>Solar</option>
                            <option value="listrik" <?= ($vehicle['fuel_type'] ?? '') === 'listrik' ? 'selected' : '' ?>>Listrik</option>
                            <option value="hybrid" <?= ($vehicle['fuel_type'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Kapasitas Penumpang</label>
                        <input type="number" name="passenger_capacity"
                               value="<?= e($vehicle['passenger_capacity'] ?? 4) ?>"
                               class="input-field" min="1" max="100">
                    </div>
                    <div>
                        <label class="label">Stok</label>
                        <input type="number" name="stock_quantity"
                               value="<?= e($vehicle['stock_quantity'] ?? 1) ?>"
                               class="input-field" min="1">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label">Deskripsi</label>
                        <textarea name="description" class="input-field" rows="3"
                                  placeholder="Deskripsi kendaraan..."><?= e($vehicle['description'] ?? '') ?></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_available" value="1"
                                   <?= ($vehicle['is_available'] ?? 1) ? 'checked' : '' ?>
                                   class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-700">Tersedia untuk disewa</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Harga -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-800 mb-5">Harga Sewa</h3>
                <div class="space-y-4">
                    <div>
                        <label class="label">Harga per Hari *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="price_per_day"
                                   value="<?= e($vehicle['price_per_day'] ?? '') ?>"
                                   class="input-field pl-10" required min="0" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="label">Harga per Minggu</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="price_per_week"
                                   value="<?= e($vehicle['price_per_week'] ?? '') ?>"
                                   class="input-field pl-10" min="0" placeholder="0 (opsional)">
                        </div>
                    </div>
                    <div>
                        <label class="label">Harga per Bulan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="price_per_month"
                                   value="<?= e($vehicle['price_per_month'] ?? '') ?>"
                                   class="input-field pl-10" min="0" placeholder="0 (opsional)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-800 mb-5">Foto Kendaraan</h3>

                <?php if ($isEdit && !empty($vehicle['images'])): ?>
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <?php foreach ($vehicle['images'] as $img): ?>
                    <div class="relative group">
                        <img src="<?= e($img['image_path']) ?>" alt=""
                             class="w-full h-20 object-cover rounded-lg border <?= $img['is_primary'] ? 'border-blue-400 ring-2 ring-blue-200' : 'border-gray-200' ?>">
                        <?php if ($img['is_primary']): ?>
                        <span class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded">Utama</span>
                        <?php endif; ?>
                        <button type="button"
                                hx-post="/admin/vehicles/images/<?= $img['id'] ?>/delete"
                                hx-target="closest div"
                                hx-swap="outerHTML"
                                hx-confirm="Hapus foto ini?"
                                hx-headers='{"X-CSRF-Token": "<?= csrf_token() ?>"}'
                                class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs text-gray-400 mb-3">Foto pertama yang diupload akan menjadi foto utama</p>
                <?php endif; ?>

                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-300 transition-colors"
                     x-data="{ previews: [] }"
                     @dragover.prevent
                     @drop.prevent="
                        const files = $event.dataTransfer.files;
                        for (const file of files) {
                            const reader = new FileReader();
                            reader.onload = e => previews.push(e.target.result);
                            reader.readAsDataURL(file);
                        }">
                    <div x-show="previews.length > 0" class="grid grid-cols-3 gap-2 mb-4">
                        <template x-for="(p, i) in previews" :key="i">
                            <img :src="p" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                        </template>
                    </div>
                    <label class="cursor-pointer block">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
                        </svg>
                        <p class="text-sm font-medium" style="color: <?= e($primaryColor) ?>">Klik untuk upload foto</p>
                        <p class="text-xs text-gray-400 mt-1">Atau drag & drop. PNG, JPG maks 5MB per foto</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                               @change="
                                previews = [];
                                for (const file of $event.target.files) {
                                    const r = new FileReader();
                                    r.onload = e => previews.push(e.target.result);
                                    r.readAsDataURL(file);
                                }">
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-between pt-2">
            <a href="/admin/vehicles" class="btn-secondary">← Batal</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all hover:shadow-lg hover:opacity-90" style="background-color: <?= e($primaryColor) ?>">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kendaraan' ?>
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>