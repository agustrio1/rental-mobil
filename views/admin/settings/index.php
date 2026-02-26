<?php
$pageTitle = 'Pengaturan Website';
$breadcrumb = 'Atur tampilan, logo, tema warna, dan SEO';
ob_start();
$primaryColor = $settings['primary_color'] ?? '#3b82f6';
?>

<div x-data="{ tab: 'general' }">

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 border-b border-gray-200 overflow-x-auto">
        <button @click="tab = 'general'"
                class="px-4 py-3 text-sm transition-all -mb-px whitespace-nowrap"
                :class="tab === 'general' ? 'border-b-2 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                :style="tab === 'general' ? 'border-color: <?= e($primaryColor) ?>; color: <?= e($primaryColor) ?>' : ''">
            Umum & Logo
        </button>
        <button @click="tab = 'theme'"
                class="px-4 py-3 text-sm transition-all -mb-px whitespace-nowrap"
                :class="tab === 'theme' ? 'border-b-2 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                :style="tab === 'theme' ? 'border-color: <?= e($primaryColor) ?>; color: <?= e($primaryColor) ?>' : ''">
            Tema & Warna
        </button>
        <button @click="tab = 'whatsapp'"
                class="px-4 py-3 text-sm transition-all -mb-px whitespace-nowrap"
                :class="tab === 'whatsapp' ? 'border-b-2 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                :style="tab === 'whatsapp' ? 'border-color: <?= e($primaryColor) ?>; color: <?= e($primaryColor) ?>' : ''">
            WhatsApp
        </button>
        <button @click="tab = 'analytics'"
                class="px-4 py-3 text-sm transition-all -mb-px whitespace-nowrap"
                :class="tab === 'analytics' ? 'border-b-2 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                :style="tab === 'analytics' ? 'border-color: <?= e($primaryColor) ?>; color: <?= e($primaryColor) ?>' : ''">
            Analytics
        </button>
        <button @click="tab = 'seo'"
                class="px-4 py-3 text-sm transition-all -mb-px whitespace-nowrap"
                :class="tab === 'seo' ? 'border-b-2 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                :style="tab === 'seo' ? 'border-color: <?= e($primaryColor) ?>; color: <?= e($primaryColor) ?>' : ''">
            SEO
        </button>
    </div>

    <!-- Form utama (Umum, Tema, WhatsApp, Analytics) -->
    <form method="POST" action="/admin/settings/general" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Tab: Umum & Logo -->
        <div x-show="tab === 'general'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Informasi Umum -->
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-800 mb-5">Informasi Website</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="label">Nama Website *</label>
                            <input type="text" name="site_name" value="<?= e($settings['site_name'] ?? '') ?>"
                                   class="input-field" required>
                        </div>
                        <div>
                            <label class="label">Tagline</label>
                            <input type="text" name="tagline" value="<?= e($settings['tagline'] ?? '') ?>"
                                   class="input-field" placeholder="Slogan singkat website Anda">
                        </div>
                        <div>
                            <label class="label">Alamat</label>
                            <textarea name="address" class="input-field" rows="2"><?= e($settings['address'] ?? '') ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="label">Telepon</label>
                                <input type="text" name="phone" value="<?= e($settings['phone'] ?? '') ?>"
                                       class="input-field" placeholder="0812xxxx">
                            </div>
                            <div>
                                <label class="label">Email</label>
                                <input type="email" name="email" value="<?= e($settings['email'] ?? '') ?>"
                                       class="input-field">
                            </div>
                        </div>
                        <div>
                            <label class="label">Footer Text</label>
                            <input type="text" name="footer_text" value="<?= e($settings['footer_text'] ?? '') ?>"
                                   class="input-field" placeholder="© 2024 Rental Kendaraan">
                        </div>
                    </div>
                </div>

                <!-- Logo & Favicon -->
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-800 mb-5">Logo & Favicon</h3>
                    <div class="space-y-5">

                        <!-- Logo -->
                        <div x-data="{ preview: '<?= e($settings['logo_path'] ?? '') ?>' }">
                            <label class="label">Logo Website</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-blue-300 transition-colors"
                                 @dragover.prevent
                                 @drop.prevent="const f=$event.dataTransfer.files[0]; if(f){const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL(f);}">
                                <template x-if="preview">
                                    <img :src="preview" class="h-16 mx-auto mb-3 object-contain rounded">
                                </template>
                                <template x-if="!preview">
                                    <div class="mb-3">
                                        <svg class="w-10 h-10 text-gray-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </template>
                                <label class="cursor-pointer">
                                    <span class="text-sm font-medium" style="color: <?= e($primaryColor) ?>">Upload Logo</span>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG — maks 2MB</p>
                                    <input type="file" name="logo" accept="image/*" class="hidden"
                                           @change="const f=$event.target.files[0];if(f){const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL(f);}">
                                </label>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div x-data="{ preview: '<?= e($settings['favicon_path'] ?? '') ?>' }">
                            <label class="label">Favicon</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-blue-300 transition-colors">
                                <template x-if="preview">
                                    <img :src="preview" class="h-10 w-10 mx-auto mb-2 object-contain">
                                </template>
                                <label class="cursor-pointer">
                                    <span class="text-sm font-medium" style="color: <?= e($primaryColor) ?>">Upload Favicon</span>
                                    <p class="text-xs text-gray-400 mt-1">ICO, PNG — 32x32px</p>
                                    <input type="file" name="favicon" accept="image/*,.ico" class="hidden"
                                           @change="const f=$event.target.files[0];if(f){const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL(f);}">
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Tab: Tema & Warna -->
        <div x-show="tab === 'theme'" style="display:none">
            <div class="card p-6" x-data="themePicker('<?= e($primaryColor) ?>')">
                <h3 class="font-semibold text-gray-800 mb-1">Tema Warna</h3>
                <p class="text-sm text-gray-500 mb-6">Pilih warna utama yang akan diterapkan ke seluruh tampilan website</p>

                <!-- Presets -->
                <div class="mb-6">
                    <label class="label">Pilih Warna Preset</label>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="preset in presets" :key="preset.value">
                            <button type="button" @click="selectPreset(preset.value)" :title="preset.name"
                                    class="group flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-xl shadow-sm transition-all group-hover:scale-110 border-2"
                                     :style="{ backgroundColor: preset.value, borderColor: color === preset.value ? preset.value : 'transparent', outline: color === preset.value ? '3px solid ' + preset.value + '40' : 'none' }">
                                </div>
                                <span class="text-xs text-gray-500" x-text="preset.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Custom picker -->
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="label">Atau Pilih Warna Custom</label>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl border-2 border-gray-200 overflow-hidden shadow-sm">
                                <input type="color" :value="color"
                                       @input="color = $event.target.value"
                                       class="w-full h-full cursor-pointer border-none p-0">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700" x-text="color"></p>
                                <p class="text-xs text-gray-400">HEX Color</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="label">Preview</label>
                        <div class="flex items-center gap-2 p-4 rounded-xl border border-gray-200">
                            <button type="button" class="px-4 py-2 rounded-lg text-white text-sm font-medium"
                                    :style="{ backgroundColor: color }">Tombol</button>
                            <span class="text-sm font-medium" :style="{ color: color }">Link Warna</span>
                            <div class="w-6 h-6 rounded-md" :style="{ backgroundColor: color + '20' }"></div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="primary_color" :value="color">

                <!-- Secondary -->
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <label class="label">Warna Sekunder</label>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl border-2 border-gray-200 overflow-hidden shadow-sm">
                            <input type="color" name="secondary_color"
                                   value="<?= e($settings['secondary_color'] ?? '#8b5cf6') ?>"
                                   class="w-full h-full cursor-pointer border-none p-0">
                        </div>
                        <p class="text-sm text-gray-500">Digunakan untuk aksen dan elemen sekunder</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: WhatsApp -->
        <div x-show="tab === 'whatsapp'" style="display:none">
            <div class="card p-6 max-w-2xl">
                <h3 class="font-semibold text-gray-800 mb-1">Pengaturan WhatsApp</h3>
                <p class="text-sm text-gray-500 mb-6">Nomor dan template pesan yang dikirim saat customer booking</p>
                <div class="space-y-5">
                    <div>
                        <label class="label">Nomor WhatsApp *</label>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-3 bg-gray-50 border border-r-0 border-gray-200 rounded-l-xl text-sm text-gray-500">+</span>
                            <input type="text" name="whatsapp_number"
                                   value="<?= e($settings['whatsapp_number'] ?? '') ?>"
                                   class="input-field rounded-l-none" placeholder="6281234567890" required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Format internasional tanpa +, contoh: 6281234567890</p>
                    </div>
                    <div>
                        <label class="label">Template Pesan</label>
                        <textarea name="whatsapp_message_template" class="input-field font-mono text-sm" rows="8"><?= e($settings['whatsapp_message_template'] ?? '') ?></textarea>
                        <div class="mt-2 p-3 bg-blue-50 rounded-xl">
                            <p class="text-xs font-semibold text-blue-700 mb-2">Variabel yang tersedia:</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach (['{booking_code}', '{customer_name}', '{customer_phone}', '{start_date}', '{end_date}', '{total_price}'] as $var): ?>
                                <code class="px-2 py-0.5 bg-white rounded text-xs text-blue-600 border border-blue-200 cursor-pointer"
                                      onclick="document.querySelector('textarea[name=whatsapp_message_template]').value += ' <?= $var ?>'">
                                    <?= $var ?>
                                </code>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Analytics -->
        <div x-show="tab === 'analytics'" style="display:none">
            <div class="card p-6 max-w-2xl space-y-6">

                <!-- Google Analytics -->
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Google Analytics</h3>
                    <p class="text-sm text-gray-500 mb-4">Masukkan Measurement ID dari Google Analytics 4 (GA4)</p>
                    <label class="label">Measurement ID</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-mono select-none">G-</span>
                        <input type="text" name="google_analytics_id"
                               value="<?= e(ltrim($settings['google_analytics_id'] ?? '', 'G-')) ?>"
                               class="input-field pl-10 font-mono" placeholder="XXXXXXXXXX">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Contoh: G-ABC123XYZ. Kosongkan untuk menonaktifkan. &nbsp;
                        <a href="https://analytics.google.com" target="_blank" class="underline" style="color:<?= e($primaryColor) ?>">Buka Google Analytics →</a>
                    </p>
                    <?php if (!empty($settings['google_analytics_id'])): ?>
                    <p class="mt-2 text-xs font-medium text-green-600">✓ Aktif: <?= e($settings['google_analytics_id']) ?></p>
                    <?php else: ?>
                    <p class="mt-2 text-xs text-gray-400">— Belum dikonfigurasi</p>
                    <?php endif; ?>
                </div>

                <!-- Facebook Pixel -->
                <div class="pt-5 border-t border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-1">Facebook Pixel</h3>
                    <p class="text-sm text-gray-500 mb-4">Masukkan Pixel ID dari Meta Business Manager</p>
                    <label class="label">Pixel ID</label>
                    <input type="text" name="facebook_pixel_id"
                           value="<?= e($settings['facebook_pixel_id'] ?? '') ?>"
                           class="input-field font-mono" placeholder="1234567890123456">
                    <p class="text-xs text-gray-400 mt-1">
                        Berupa angka panjang. Kosongkan untuk menonaktifkan. &nbsp;
                        <a href="https://business.facebook.com/events_manager" target="_blank" class="underline" style="color:<?= e($primaryColor) ?>">Buka Events Manager →</a>
                    </p>
                    <?php if (!empty($settings['facebook_pixel_id'])): ?>
                    <p class="mt-2 text-xs font-medium text-green-600">✓ Aktif: <?= e($settings['facebook_pixel_id']) ?></p>
                    <?php else: ?>
                    <p class="mt-2 text-xs text-gray-400">— Belum dikonfigurasi</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- Tombol simpan (tampil di semua tab kecuali SEO) -->
        <div x-show="tab !== 'seo'" class="mt-6 flex justify-end">
            <button type="submit"
                    class="px-8 py-2.5 rounded-xl text-white font-medium text-sm flex items-center gap-2 hover:shadow-lg hover:opacity-90 transition-all"
                    style="background-color: <?= e($primaryColor) ?>">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Pengaturan
            </button>
        </div>

    </form>

    <!-- Tab: SEO (form terpisah) -->
    <div x-show="tab === 'seo'" style="display:none" id="seo">
        <form method="POST" action="/admin/settings/seo">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <?php foreach ($seoPages as $seo): ?>
                <div class="card p-5">
                    <h4 class="font-medium text-gray-800 mb-4">
                        <?= e($seo['page_name']) ?>
                        <span class="text-xs text-gray-400 font-normal ml-2">/?page=<?= e($seo['page_slug']) ?></span>
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="label text-xs">Meta Title</label>
                            <input type="text" name="seo[<?= $seo['id'] ?>][meta_title]"
                                   value="<?= e($seo['meta_title'] ?? '') ?>"
                                   class="input-field text-sm" placeholder="Judul halaman untuk SEO">
                        </div>
                        <div>
                            <label class="label text-xs">Meta Description</label>
                            <textarea name="seo[<?= $seo['id'] ?>][meta_description]"
                                      class="input-field text-sm" rows="2"
                                      placeholder="Deskripsi halaman (maks 160 karakter)"><?= e($seo['meta_description'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="label text-xs">Meta Keywords</label>
                            <input type="text" name="seo[<?= $seo['id'] ?>][meta_keywords]"
                                   value="<?= e($seo['meta_keywords'] ?? '') ?>"
                                   class="input-field text-sm" placeholder="kata kunci, pisahkan, dengan koma">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-8 py-2.5 rounded-xl text-white font-medium text-sm flex items-center gap-2 hover:shadow-lg hover:opacity-90 transition-all"
                            style="background-color: <?= e($primaryColor) ?>">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan SEO
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
