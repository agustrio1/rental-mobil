<?php
ob_start();
$s = settings();
$primaryColor = $s['primary_color'] ?? '#3b82f6';
$waNumber = $s['whatsapp_number'] ?? '';
?>

<style>
.contact-wrap { font-family: 'Nunito', sans-serif; }
.contact-wrap h1, .contact-wrap h2, .contact-wrap h3 { font-family: 'Sora', sans-serif; }

.contact-hero {
    padding: 5rem 0 4rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: start;
    padding: 5rem 0;
}
@media(max-width: 768px) {
    .contact-grid { grid-template-columns: 1fr; gap: 2.5rem; padding: 3rem 0; }
}

.info-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #fff;
    margin-bottom: 1rem;
    text-decoration: none;
    color: inherit;
    transition: box-shadow 0.15s, transform 0.15s;
}
.info-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.07); transform: translateY(-2px); }

.info-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.info-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-bottom: 3px; }
.info-value { font-size: 0.95rem; font-weight: 700; color: #0f172a; font-family: 'Sora', sans-serif; }
.info-sub { font-size: 0.78rem; color: #64748b; margin-top: 2px; }

.wa-cta-box {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}
.wa-cta-inner {
    padding: 3rem 2.5rem;
    position: relative;
    z-index: 1;
}
.wa-cta-inner::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    z-index: 0;
}
.wa-big-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.75rem;
    background: white;
    border-radius: 14px;
    text-decoration: none;
    transition: transform 0.15s, box-shadow 0.15s;
    margin-top: 1.75rem;
}
.wa-big-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,0.15); }
.wa-big-btn .wa-btn-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: #dcfce7;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.wa-btn-label { font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 2px; }
.wa-btn-text { font-size: 1rem; font-weight: 800; color: #0f172a; font-family: 'Sora', sans-serif; }
.wa-btn-arrow {
    margin-left: auto;
    width: 36px; height: 36px; border-radius: 10px;
    background: #f1f5f9;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.jam-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-top: 1rem;
}
.jam-item {
    padding: 1rem 1.25rem;
    border-radius: 12px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
}
.jam-day { font-size: 0.72rem; color: rgba(255,255,255,0.65); font-weight: 600; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.06em; }
.jam-hour { font-size: 0.9rem; color: white; font-weight: 700; font-family: 'Sora', sans-serif; }
</style>

<div class="contact-wrap">

    <!-- Hero -->
    <section class="contact-hero">
        <div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;">
            <p style="font-size:.7rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8;display:flex;align-items:center;gap:8px;margin-bottom:.75rem;">
                <span style="width:20px;height:2px;background:currentColor;display:inline-block;"></span>
                Hubungi Kami
            </p>
            <h1 style="font-size:clamp(1.8rem,4vw,2.75rem);font-weight:900;color:#0f172a;letter-spacing:-.03em;line-height:1.15;margin-bottom:1rem;">
                Ada yang Bisa<br>
                <span style="color:<?= e($primaryColor) ?>">Kami Bantu?</span>
            </h1>
            <p style="font-size:1rem;color:#64748b;line-height:1.75;max-width:480px;">
                Tim kami siap menjawab pertanyaan soal ketersediaan kendaraan, harga, rute, maupun kebutuhan khusus Anda.
            </p>
        </div>
    </section>

    <!-- Konten -->
    <section>
        <div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;">
            <div class="contact-grid">

                <!-- Kiri: Info kontak -->
                <div>
                    <h2 style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:1.5rem;">Informasi Kontak</h2>

                    <?php if(!empty($waNumber)): ?>
                    <a href="https://wa.me/<?= e($waNumber) ?>" target="_blank" class="info-card">
                        <div class="info-icon" style="background:#dcfce7">
                            <svg width="22" height="22" fill="#16a34a" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">WhatsApp</p>
                            <p class="info-value">+<?= e($waNumber) ?></p>
                            <p class="info-sub">Klik untuk langsung chat →</p>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if(!empty($s['phone'])): ?>
                    <div class="info-card" style="cursor:default;">
                        <div class="info-icon" style="background:<?= e($primaryColor) ?>15">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:<?= e($primaryColor) ?>">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Telepon</p>
                            <p class="info-value"><?= e($s['phone']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($s['email'])): ?>
                    <div class="info-card" style="cursor:default;">
                        <div class="info-icon" style="background:<?= e($primaryColor) ?>15">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:<?= e($primaryColor) ?>">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Email</p>
                            <p class="info-value"><?= e($s['email']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($s['address'])): ?>
                    <div class="info-card" style="cursor:default;">
                        <div class="info-icon" style="background:<?= e($primaryColor) ?>15">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:<?= e($primaryColor) ?>">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Alamat</p>
                            <p class="info-value" style="font-size:.88rem;"><?= nl2br(e($s['address'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Kanan: WA CTA box -->
                <?php if(!empty($waNumber)): ?>
                <div>
                    <div class="wa-cta-box" style="background:<?= e($primaryColor) ?>">
                        <div class="wa-cta-inner">
                            <p style="font-size:.7rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-bottom:.75rem;">Chat Langsung</p>
                            <h2 style="font-size:1.5rem;font-weight:800;color:#fff;line-height:1.25;letter-spacing:-.02em;position:relative;z-index:1;margin-bottom:.75rem;">
                                Mau Tanya Dulu<br>Soal Kendaraan?
                            </h2>
                            <p style="font-size:.875rem;color:rgba(255,255,255,.75);line-height:1.75;position:relative;z-index:1;">
                                Langsung chat dengan admin kami via WhatsApp. Respon cepat, ramah, dan siap bantu pilihkan kendaraan terbaik buat Anda.
                            </p>

                            <a href="https://wa.me/<?= e($waNumber) ?>?text=Halo%2C+saya+ingin+menanyakan+informasi+rental+kendaraan"
                               target="_blank" class="wa-big-btn">
                                <div class="wa-btn-icon">
                                    <svg width="26" height="26" fill="#16a34a" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="wa-btn-label">Klik untuk chat</p>
                                    <p class="wa-btn-text">Buka WhatsApp →</p>
                                </div>
                                <div class="wa-btn-arrow">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#0f172a">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>

                            <!-- Jam operasional -->
                            <p style="font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.55);margin:1.75rem 0 .75rem;position:relative;z-index:1;">
                                Jam Operasional
                            </p>
                            <div class="jam-grid" style="position:relative;z-index:1;">
                                <div class="jam-item">
                                    <p class="jam-day">Senin – Jumat</p>
                                    <p class="jam-hour">08.00 – 21.00</p>
                                </div>
                                <div class="jam-item">
                                    <p class="jam-day">Sabtu – Minggu</p>
                                    <p class="jam-hour">08.00 – 20.00</p>
                                </div>
                            </div>
                            <p style="font-size:.75rem;color:rgba(255,255,255,.5);margin-top:.875rem;position:relative;z-index:1;">
                                * Di luar jam operasional, pesan via WA tetap kami balas secepatnya
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
