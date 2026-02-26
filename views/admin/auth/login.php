<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | <?= e($settings['site_name'] ?? 'Rental') ?></title>
    <link rel="stylesheet" href="/css/app.css">
    <?php
    $primaryColor = $settings['primary_color'] ?? '#3b82f6';
    $palette = \App\Models\Setting::generateColorPalette($primaryColor);
    ?>
    <style>
        :root {
            <?php foreach ($palette as $shade => $hex): ?>
            --theme-primary-<?= $shade ?>: <?= $hex ?>;
            <?php endforeach; ?>
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <?php if (!empty($settings['logo_path'])): ?>
                <img src="<?= e($settings['logo_path']) ?>" alt="Logo" class="h-12 mx-auto mb-4">
            <?php else: ?>
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4 shadow-lg"
                     style="background-color: <?= e($primaryColor) ?>">
                    R
                </div>
            <?php endif; ?>
            <h1 class="text-2xl font-bold text-white"><?= e($settings['site_name'] ?? 'Rental') ?></h1>
            <p class="text-slate-400 mt-1 text-sm">Masuk ke panel admin</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <?php $error = get_flash('error'); ?>
            <?php if ($error): ?>
            <div class="flex items-center gap-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm mb-5">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= e($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login" x-data="{ loading: false }" @submit="loading = true">
                <?= csrf_field() ?>

                <div class="space-y-5">
                    <div>
                        <label class="label">Username</label>
                        <input type="text"
                               name="username"
                               value="<?= old('username') ?>"
                               class="input-field"
                               placeholder="Masukkan username"
                               required autofocus>
                    </div>

                    <div>
                        <label class="label">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   class="input-field pr-12"
                                   placeholder="Masukkan password"
                                   required>
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            :disabled="loading"
                            class="w-full py-3 px-6 rounded-xl text-white font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg active:scale-95"
                            style="background-color: <?= e($primaryColor) ?>; hover:opacity-90">
                        <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="loading ? 'Masuk...' : 'Masuk'">Masuk</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-6">
            <a href="/" class="hover:text-slate-300 transition-colors">← Kembali ke website</a>
        </p>
    </div>

    <script src="/js/alpine.min.js" defer></script>
</body>
</html>