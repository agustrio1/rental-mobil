<?php
/**
 * View: views/public/vehicles/show.php
 * Controller: App\Controllers\Public\VehicleController::show()
 * Variables: $vehicle, $settings
 */

ob_start();

$primaryColor = settings()['primary_color'] ?? '#3b82f6';
$waNumber     = settings()['whatsapp_number'] ?? '';

// Build WhatsApp booking message
$bookingCode  = generate_booking_code();
$waTemplate   = settings()['whatsapp_message_template'] ?? '';
$waMessage    = str_replace(
    ['{booking_code}', '{customer_name}', '{customer_phone}', '{start_date}', '{end_date}', '{total_price}'],
    [$bookingCode,     '[Nama Anda]',      '[No HP Anda]',     '[Tgl Mulai]',  '[Tgl Selesai]', '[Total]'],
    $waTemplate
);
$waLink = $waNumber
    ? 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage)
    : '#';

$images    = $vehicle['images'] ?? [];
$features  = !empty($vehicle['features']) ? json_decode($vehicle['features'], true) : [];
?>

<!-- ═══════════════════════════════════════════════
     BREADCRUMB
═══════════════════════════════════════════════ -->
<div class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3">
        <nav class="flex items-center gap-2 text-sm text-gray-400">
            <a href="/"          class="hover:text-gray-600 transition-colors">Beranda</a>
            <span>/</span>
            <a href="/kendaraan" class="hover:text-gray-600 transition-colors">Kendaraan</a>
            <span>/</span>
            <span class="text-gray-700 font-medium truncate max-w-[200px]">
                <?= e($vehicle['vehicle_name']) ?>
            </span>
        </nav>
    </div>
</div>


<!-- ═══════════════════════════════════════════════
     MAIN DETAIL
═══════════════════════════════════════════════ -->
<section class="py-10 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            <!-- LEFT: Images + Info -->
            <div class="lg:col-span-3 space-y-6">

                <!-- Image gallery -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100"
                     x-data="{ active: 0 }">

                    <!-- Main image -->
                    <div class="relative h-72 sm:h-96 bg-gray-100 overflow-hidden">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $i => $img): ?>
                            <img src="<?= e($img['image_path']) ?>"
                                 alt="<?= e($vehicle['vehicle_name']) ?> foto <?= $i + 1 ?>"
                                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                                 x-show="active === <?= $i ?>"
                                 <?= $i > 0 ? 'style="display:none"' : '' ?>
                                 loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m14-4V8a1 1 0 00-1-1h-1.172"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <!-- Type badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow"
                                  style="background-color: <?= e($primaryColor) ?>">
                                <?= ucfirst(e($vehicle['vehicle_type'])) ?>
                            </span>
                        </div>

                        <?php if (!empty($vehicle['is_available'])): ?>
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold text-green-700 bg-green-100 shadow">
                                ✓ Tersedia
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Thumbnails -->
                    <?php if (count($images) > 1): ?>
                    <div class="flex gap-2 p-4 overflow-x-auto">
                        <?php foreach ($images as $i => $img): ?>
                        <button @click="active = <?= $i ?>"
                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
                                :class="active === <?= $i ?> ? 'border-[<?= $primaryColor ?>] opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                            <img src="<?= e($img['image_path']) ?>"
                                 alt="thumb <?= $i + 1 ?>"
                                 class="w-full h-full object-cover">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Specs -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-900 text-lg mb-5">Spesifikasi</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <?php
                        $specs = [
                            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                             'label' => 'Tahun', 'value' => $vehicle['year'] ?? '-'],
                            ['icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                             'label' => 'Merek', 'value' => $vehicle['brand'] ?? '-'],
                            ['icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
                             'label' => 'Transmisi', 'value' => ucfirst($vehicle['transmission'] ?? '-')],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                             'label' => 'Kapasitas', 'value' => ($vehicle['passenger_capacity'] ?? '-').' orang'],
                            ['icon' => 'M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1l1 2h1l1-2h6l1 2h1l1-2h1a1 1 0 001-1V5a1 1 0 00-1-1H3zm11 3a1 1 0 11-2 0 1 1 0 012 0zM6 13a1 1 0 11-2 0 1 1 0 012 0zm2-4a1 1 0 11-2 0 1 1 0 012 0z',
                             'label' => 'BBM', 'value' => ucfirst($vehicle['fuel_type'] ?? '-')],
                            ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                             'label' => 'Stok', 'value' => ($vehicle['stock_quantity'] ?? 0).' unit'],
                        ];
                        foreach ($specs as $spec):
                        ?>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background-color: <?= e($primaryColor) ?>15">
                                <svg class="w-4 h-4" style="color: <?= e($primaryColor) ?>"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="<?= $spec['icon'] ?>"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400"><?= $spec['label'] ?></p>
                                <p class="text-sm font-semibold text-gray-800"><?= e((string)$spec['value']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Description -->
                <?php if (!empty($vehicle['description'])): ?>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-900 text-lg mb-3">Deskripsi</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        <?= nl2br(e($vehicle['description'])) ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Features -->
                <?php if (!empty($features)): ?>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-900 text-lg mb-4">Fasilitas</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <?php foreach ($features as $feature): ?>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: <?= e($primaryColor) ?>"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            <?= e($feature) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>


            <!-- RIGHT: Booking Card -->
            <div class="lg:col-span-2">
                <div class="sticky top-24 space-y-5">

                    <!-- Price card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h1 class="font-extrabold text-gray-900 text-xl mb-1">
                            <?= e($vehicle['vehicle_name']) ?>
                        </h1>
                        <p class="text-sm text-gray-400 mb-5">
                            <?= e($vehicle['brand'] ?? '') ?>
                            <?php if (!empty($vehicle['year'])): ?>· <?= e($vehicle['year']) ?><?php endif; ?>
                        </p>

                        <!-- Pricing tiers -->
                        <div class="space-y-2 mb-5">
                            <?php foreach ([
                                ['label' => 'Per Hari',   'price' => $vehicle['price_per_day'],   'highlight' => true],
                                ['label' => 'Per Minggu', 'price' => $vehicle['price_per_week'],  'highlight' => false],
                                ['label' => 'Per Bulan',  'price' => $vehicle['price_per_month'], 'highlight' => false],
                            ] as $tier):
                                if (empty($tier['price'])) continue;
                            ?>
                            <div class="flex items-center justify-between py-2 px-3 rounded-xl
                                        <?= $tier['highlight'] ? 'border-2' : 'bg-gray-50' ?>"
                                 <?= $tier['highlight'] ? 'style="border-color:'.e($primaryColor).'15; background-color:'.e($primaryColor).'08"' : '' ?>>
                                <span class="text-sm text-gray-500"><?= $tier['label'] ?></span>
                                <span class="font-bold <?= $tier['highlight'] ? 'text-lg' : 'text-base text-gray-700' ?>"
                                      <?= $tier['highlight'] ? 'style="color:'.e($primaryColor).'"' : '' ?>>
                                    <?= format_rupiah($tier['price']) ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Booking Form -->
                        <div x-data="bookingForm()" class="space-y-3">

                            <!-- STEP 1: Pilih tanggal -->
                            <template x-if="step === 'dates' || step === 'details' || step === 'submitting'">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Mulai</label>
                                            <input type="date" x-model="startDate" @change="fixEndDate()"
                                                   min="<?= date('Y-m-d') ?>"
                                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Selesai</label>
                                            <input type="date" x-model="endDate" @change="checkAvailability()"
                                                   :min="startDate || '<?= date('Y-m-d', strtotime('+1 day')) ?>'"
                                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2">
                                        </div>
                                    </div>

                                    <!-- Availability -->
                                    <div>
                                        <template x-if="checking">
                                            <div class="flex items-center gap-2 text-xs text-gray-400 py-1">
                                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                                Mengecek ketersediaan...
                                            </div>
                                        </template>
                                        <template x-if="!checking && available === true">
                                            <div class="flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-xl text-xs font-medium">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Tersedia untuk tanggal yang dipilih!
                                            </div>
                                        </template>
                                        <template x-if="!checking && available === false">
                                            <div class="flex items-center gap-2 px-3 py-2 bg-red-50 text-red-600 rounded-xl text-xs font-medium">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tidak tersedia untuk tanggal tersebut
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Estimasi harga -->
                                    <template x-if="days > 0">
                                        <div class="bg-gray-50 rounded-xl px-4 py-3 text-sm">
                                            <div class="flex justify-between text-gray-500 mb-1">
                                                <span x-text="`${days} hari × ${pricePerDay.toLocaleString('id-ID')}`"></span>
                                            </div>
                                            <div class="flex justify-between font-bold text-gray-900">
                                                <span>Estimasi Total</span>
                                                <span x-text="totalFmt" style="color:<?= e($primaryColor) ?>"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- STEP 2: Data pemesan (muncul setelah tanggal valid & tersedia) -->
                            <template x-if="(step === 'details' || step === 'submitting') && available === true && days > 0">
                                <div class="border-t border-gray-100 pt-4 space-y-3">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Data Pemesan</p>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Nama Lengkap *</label>
                                        <input type="text" x-model="customerName" placeholder="Nama sesuai KTP"
                                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">No HP / WhatsApp *</label>
                                        <input type="tel" x-model="customerPhone" placeholder="08xxxx"
                                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Email (opsional)</label>
                                        <input type="email" x-model="customerEmail" placeholder="email@anda.com"
                                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi Pengambilan</label>
                                        <input type="text" x-model="pickupLocation" placeholder="Alamat / area pickup"
                                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Catatan Tambahan</label>
                                        <textarea x-model="specialRequests" rows="2" placeholder="Permintaan khusus..."
                                                  class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 resize-none"></textarea>
                                    </div>

                                    <!-- Error -->
                                    <template x-if="errorMsg">
                                        <p class="text-xs text-red-500 px-1" x-text="errorMsg"></p>
                                    </template>
                                </div>
                            </template>

                            <!-- Tombol aksi -->
                            <!-- A) Tanggal belum dipilih atau tidak tersedia -->
                            <template x-if="available !== true || days === 0">
                                <button disabled
                                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-semibold text-white opacity-50 cursor-not-allowed"
                                        style="background-color:#25d366">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    Pilih Tanggal Dulu
                                </button>
                            </template>

                            <!-- B) Tersedia, belum isi data pemesan -->
                            <template x-if="available === true && days > 0 && step === 'dates'">
                                <button @click="step = 'details'"
                                        class="w-full py-3.5 rounded-xl font-semibold text-white shadow-md transition-all hover:shadow-lg active:scale-95"
                                        style="background-color:<?= e($primaryColor) ?>">
                                    Lanjut Isi Data Pemesan →
                                </button>
                            </template>

                            <!-- C) Sudah isi data, siap booking -->
                            <template x-if="available === true && days > 0 && (step === 'details' || step === 'submitting')">
                                <button @click="submitBooking()"
                                        :disabled="step === 'submitting'"
                                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-semibold text-white shadow-md transition-all hover:shadow-lg active:scale-95"
                                        :class="step === 'submitting' ? 'opacity-70 cursor-wait' : ''"
                                        style="background-color:#25d366">
                                    <template x-if="step === 'submitting'">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                    </template>
                                    <template x-if="step !== 'submitting'">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </template>
                                    <span x-text="step === 'submitting' ? 'Menyimpan booking...' : 'Simpan & Lanjut ke WhatsApp'"></span>
                                </button>
                            </template>

                            <!-- D) Booking sukses -->
                            <template x-if="step === 'done'">
                                <div class="text-center py-3 px-4 bg-green-50 rounded-xl">
                                    <p class="text-green-700 font-semibold text-sm mb-1">✓ Booking berhasil disimpan!</p>
                                    <p class="text-green-600 text-xs">WhatsApp sudah terbuka. Selesaikan konfirmasi dengan admin kami.</p>
                                </div>
                            </template>

                        </div>

                        <p class="text-center text-xs text-gray-400 mt-3">
                            Konfirmasi langsung dengan admin kami
                        </p>
                    </div>

                    <!-- Info card -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-3 text-sm">Informasi Booking</h3>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <?php foreach ([
                                'Booking gratis, tidak ada biaya tambahan',
                                'Konfirmasi via WhatsApp dalam 15 menit',
                                'Pembayaran fleksibel: transfer / cash',
                                'Gratis pembatalan hingga sebelum 24 jam',
                            ] as $info): ?>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" style="color: <?= e($primaryColor) ?>"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <?= $info ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>



<?php
$content = ob_get_clean();

$pricePerDay = (int)$vehicle['price_per_day'];
$vehicleId   = (int)$vehicle['id'];


$waNumberEscaped = addslashes($waNumber);

$waTemplateEscaped = addslashes(str_replace(["\r\n", "\r", "\n"], "\\n", $waTemplate));

$headScript = <<<SCRIPT
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bookingForm', () => ({
        step: 'dates',
        startDate: '',
        endDate: '',
        customerName: '',
        customerPhone: '',
        customerEmail: '',
        pickupLocation: '',
        specialRequests: '',
        pricePerDay: {$pricePerDay},
        vehicleId: {$vehicleId},
        waNumber: '{$waNumberEscaped}',
        waTemplate: '{$waTemplateEscaped}',
        checking: false,
        available: null,
        errorMsg: '',
        
        get days() {
            if (!this.startDate || !this.endDate) return 0;
            const diff = (new Date(this.endDate) - new Date(this.startDate)) / 86400000;
            return diff > 0 ? Math.ceil(diff) : 0;
        },
        
        get total() {
            return this.days * this.pricePerDay;
        },
        
        get totalFmt() {
            return 'Rp ' + this.total.toLocaleString('id-ID');
        },
        
        async checkAvailability() {
            if (!this.startDate || !this.endDate) return;
            this.checking = true;
            this.available = null;
            
            try {
                const fd = new FormData();
                fd.append('vehicle_id', this.vehicleId);
                fd.append('start_date', this.startDate);
                fd.append('end_date', this.endDate);
                
                const res = await fetch('/api/check-availability', { method: 'POST', body: fd });
                const data = await res.json();
                this.available = data.available;
            } catch(e) {
                this.available = null;
            } finally {
                this.checking = false;
            }
        },
        
        fixEndDate() {
            if (this.endDate && this.endDate <= this.startDate) {
                const nextDay = new Date(this.startDate);
                nextDay.setDate(nextDay.getDate() + 1);
                this.endDate = nextDay.toISOString().slice(0, 10);
            }
            this.checkAvailability();
        },
        
        formatDate(dateStr) {
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const d = new Date(dateStr);
            return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
        },
        
        async submitBooking() {
            if (!this.customerName || !this.customerPhone) {
                this.errorMsg = 'Nama dan nomor HP harus diisi.';
                return;
            }
            
            this.step = 'submitting';
            this.errorMsg = '';
            
            try {
                const fd = new FormData();
                fd.append('vehicle_id', this.vehicleId);
                fd.append('start_date', this.startDate);
                fd.append('end_date', this.endDate);
                fd.append('customer_name', this.customerName);
                fd.append('customer_phone', this.customerPhone);
                fd.append('customer_email', this.customerEmail);
                fd.append('pickup_location', this.pickupLocation);
                fd.append('special_requests', this.specialRequests);
                fd.append('total_price', this.total);
                
                const res = await fetch('/booking/store', { method: 'POST', body: fd });
                const data = await res.json();
                
                if (!data.success) throw new Error(data.message || 'Gagal menyimpan booking');
                
                // Format pesan WhatsApp yang lebih rapi
                let msg = 'Halo, saya ingin booking kendaraan:%0A%0A';
                msg += '*Kode Booking*: ' + data.booking_code + '%0A';
                msg += '*Nama*: ' + this.customerName + '%0A';
                msg += '*No HP*: ' + this.customerPhone + '%0A';
                msg += '*Tanggal*: ' + this.formatDate(this.startDate) + ' s/d ' + this.formatDate(this.endDate) + '%0A';
                msg += '*Durasi*: ' + this.days + ' hari%0A';
                msg += '*Total*: ' + this.totalFmt + '%0A';
                
                if (this.pickupLocation) {
                    msg += '*Lokasi Pengambilan*: ' + this.pickupLocation + '%0A';
                }
                
                if (this.specialRequests) {
                    msg += '*Catatan*: ' + this.specialRequests + '%0A';
                }
                
                msg += '%0AMohon konfirmasi ketersediaan. Terima kasih.';
                
                window.open('https://wa.me/' + this.waNumber + '?text=' + msg, '_blank');
                this.step = 'done';
            } catch(e) {
                this.errorMsg = e.message;
                this.step = 'details';
            }
        }
    }));
});
</script>
SCRIPT;

require __DIR__ . '/../layouts/app.php';