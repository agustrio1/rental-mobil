<?php

namespace App\Controllers\Admin;

use App\Models\Setting;

class SettingController
{
    private Setting $setting;

    public function __construct()
    {
        $this->setting = new Setting();
    }

    public function index(): void
    {
        $settings = $this->setting->get();
        $seoPages = $this->setting->getSeoPages();

        view('admin.settings.index', [
            'settings' => $settings,
            'seoPages' => $seoPages,
        ]);
    }

    public function updateGeneral(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token tidak valid');
            redirect('/admin/settings');
        }

        $data = [
            'site_name'                  => trim($_POST['site_name'] ?? ''),
            'tagline'                    => trim($_POST['tagline'] ?? ''),
            'meta_title'                 => trim($_POST['meta_title'] ?? ''),
            'meta_description'           => trim($_POST['meta_description'] ?? ''),
            'meta_keywords'              => trim($_POST['meta_keywords'] ?? ''),
            'canonical_url'              => trim($_POST['canonical_url'] ?? ''),
            'whatsapp_number'            => preg_replace('/\D/', '', $_POST['whatsapp_number'] ?? ''),
            'whatsapp_message_template'  => $_POST['whatsapp_message_template'] ?? '',
            'footer_text'                => trim($_POST['footer_text'] ?? ''),
            'address'                    => trim($_POST['address'] ?? ''),
            'phone'                      => trim($_POST['phone'] ?? ''),
            'email'                      => trim($_POST['email'] ?? ''),
            'primary_color'              => trim($_POST['primary_color'] ?? '#3b82f6'),
            'secondary_color'            => trim($_POST['secondary_color'] ?? '#8b5cf6'),
            // Analytics & Pixel — simpan null kalau kosong
            'google_analytics_id'        => trim($_POST['google_analytics_id'] ?? '') ?: null,
            'facebook_pixel_id'          => trim($_POST['facebook_pixel_id'] ?? '') ?: null,
            'updated_by'                 => auth()['id'],
        ];

        $settings = $this->setting->get();
        $this->setting->update($settings['id'], $data);

        // Handle logo upload
        $logoData = [];
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $path = upload_file($_FILES['logo'], 'logo');
            if ($path) $logoData['logo_path'] = $path;
        }
        if (!empty($_FILES['favicon']['name']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $path = upload_file($_FILES['favicon'], 'logo');
            if ($path) $logoData['favicon_path'] = $path;
        }
        if (!empty($logoData)) {
            $this->setting->updateLogo($settings['id'], $logoData);
        }

        flash('success', 'Pengaturan berhasil disimpan!');
        redirect('/admin/settings');
    }

    public function updateSeo(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token tidak valid');
            redirect('/admin/settings#seo');
        }

        foreach ($_POST['seo'] ?? [] as $id => $data) {
            $this->setting->updateSeo((int)$id, [
                'meta_title'       => trim($data['meta_title'] ?? ''),
                'meta_description' => trim($data['meta_description'] ?? ''),
                'meta_keywords'    => trim($data['meta_keywords'] ?? ''),
            ]);
        }

        flash('success', 'Pengaturan SEO berhasil disimpan!');
        redirect('/admin/settings#seo');
    }

    public static function generateThemeCss(string $primaryColor): string
    {
        $palette = Setting::generateColorPalette($primaryColor);
        $css = ":root {\n";
        foreach ($palette as $shade => $hex) {
            $css .= "  --theme-primary-{$shade}: {$hex};\n";
        }
        $css .= "}\n";
        return $css;
    }
}
