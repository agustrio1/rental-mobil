<?php
$pageTitle = 'Dashboard';
ob_start();
$primaryColor = settings()['primary_color'] ?? '#3b82f6';
?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Booking -->
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: <?= e($primaryColor) ?>20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: <?= e($primaryColor) ?>">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full font-medium">+<?= $stats['today'] ?> hari ini</span>
        </div>
        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['total']) ?></p>
        <p class="text-sm text-gray-500 mt-0.5">Total Booking</p>
    </div>

    <!-- Pending -->
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-yellow-50">
                <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['pending']) ?></p>
        <p class="text-sm text-gray-500 mt-0.5">Menunggu Konfirmasi</p>
    </div>

    <!-- Kendaraan -->
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4"/>
                </svg>
            </div>
            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full font-medium"><?= $stats['available_vehicles'] ?> tersedia</span>
        </div>
        <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_vehicles']) ?></p>
        <p class="text-sm text-gray-500 mt-0.5">Total Kendaraan</p>
    </div>

    <!-- Revenue -->
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-50">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xl font-bold text-gray-800"><?= format_rupiah($stats['revenue_month']) ?></p>
        <p class="text-sm text-gray-500 mt-0.5">Pendapatan Bulan Ini</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Bookings -->
    <div class="lg:col-span-2 card">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Booking Terbaru</h3>
            <a href="/admin/bookings" class="text-sm font-medium" style="color: <?= e($primaryColor) ?>">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="table-header">Kode</th>
                        <th class="table-header">Customer</th>
                        <th class="table-header">Kendaraan</th>
                        <th class="table-header">Tanggal</th>
                        <th class="table-header">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBookings as $booking): ?>
                    <tr class="table-row">
                        <td class="table-cell">
                            <a href="/admin/bookings/<?= $booking['id'] ?>"
                               class="font-mono text-xs font-medium hover:underline" style="color: <?= e($primaryColor) ?>">
                                <?= e($booking['booking_code']) ?>
                            </a>
                        </td>
                        <td class="table-cell">
                            <div class="font-medium text-gray-800 text-sm"><?= e($booking['customer_name']) ?></div>
                            <div class="text-gray-400 text-xs"><?= e($booking['customer_phone']) ?></div>
                        </td>
                        <td class="table-cell text-sm"><?= e($booking['vehicle_name']) ?></td>
                        <td class="table-cell text-xs text-gray-500">
                            <?= format_date($booking['start_date'], 'd M Y') ?>
                        </td>
                        <td class="table-cell">
                            <?php
                            $statusClass = match($booking['booking_status']) {
                                'pending'   => 'badge-pending',
                                'confirmed' => 'badge-confirmed',
                                'completed' => 'badge-completed',
                                'cancelled' => 'badge-cancelled',
                                default     => 'badge-pending',
                            };
                            $statusLabel = match($booking['booking_status']) {
                                'pending'   => 'Pending',
                                'confirmed' => 'Dikonfirmasi',
                                'ongoing'   => 'Berjalan',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default     => $booking['booking_status'],
                            };
                            ?>
                            <span class="<?= $statusClass ?>">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                <?= $statusLabel ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentBookings)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">Belum ada booking</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats Side -->
    <div class="space-y-4">
        <!-- Status Breakdown -->
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Status Booking</h3>
            <div class="space-y-3">
                <?php
                $statuses = [
                    ['label' => 'Pending', 'count' => $stats['pending'], 'color' => '#f59e0b'],
                    ['label' => 'Dikonfirmasi', 'count' => $stats['confirmed'], 'color' => $primaryColor],
                    ['label' => 'Berjalan', 'count' => $stats['ongoing'], 'color' => '#10b981'],
                ];
                foreach ($statuses as $st):
                    $pct = $stats['total'] > 0 ? round($st['count'] / $stats['total'] * 100) : 0;
                ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600"><?= $st['label'] ?></span>
                        <span class="font-medium text-gray-800"><?= $st['count'] ?></span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width: <?= $pct ?>%; background-color: <?= $st['color'] ?>"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="/admin/vehicles/create"
                   class="flex items-center gap-3 p-3 rounded-xl border border-dashed border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center group-hover:text-white transition-colors" style="background-color: <?= e($primaryColor) ?>20; color: <?= e($primaryColor) ?>">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Tambah Kendaraan</span>
                </a>
                <a href="/admin/bookings?status=pending"
                   class="flex items-center gap-3 p-3 rounded-xl border border-dashed border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-all group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-yellow-50 text-yellow-600 group-hover:bg-yellow-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Booking Pending (<?= $stats['pending'] ?>)</span>
                </a>
                <a href="/admin/settings"
                   class="flex items-center gap-3 p-3 rounded-xl border border-dashed border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-50 text-purple-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Pengaturan Website</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
?>