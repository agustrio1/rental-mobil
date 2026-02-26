<?php
ob_start();
$s = settings();
$primaryColor = $s['primary_color'] ?? '#3b82f6';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Nunito:wght@400;500;600;700&display=swap');

.hw { font-family: 'Nunito', sans-serif; }
.hw h1, .hw h2, .hw h3, .hw .sora { font-family: 'Sora', sans-serif; }

/* ── HERO ── */
.hero {
    display: grid;
    grid-template-columns: 55% 45%;
    min-height: 88vh;
    position: relative;
    overflow: hidden;
}
@media(max-width:768px){ .hero{grid-template-columns:1fr;min-height:auto;} .hero-right{display:none;} }

.hero-left {
    padding: 6rem 3rem 5rem 5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    z-index: 2;
    background: #fff;
}
@media(max-width:1100px){ .hero-left{ padding: 5rem 2.5rem 4rem 3rem; } }
@media(max-width:768px){ .hero-left{ padding: 3.5rem 1.5rem; } }

.hero-right {
    position: relative;
    overflow: hidden;
}
.hero-right img {
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center 30%;
}
.hero-right::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, #fff 0%, transparent 20%);
}
.hero-right-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(160deg, #f1f5f9 0%, #dde4ef 100%);
    display: flex; align-items: center; justify-content: center;
}

.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 0.72rem; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: #64748b; margin-bottom: 1.5rem;
}
.hero-eyebrow span {
    width: 28px; height: 2px; background: currentColor; display: inline-block;
}

.hero-h1 {
    font-size: clamp(2.2rem, 4.5vw, 3.6rem);
    font-weight: 800;
    line-height: 1.12;
    letter-spacing: -0.03em;
    color: #0f172a;
    margin-bottom: 1.25rem;
}
.hero-h1 mark {
    background: none;
    -webkit-text-fill-color: transparent;
    -webkit-background-clip: text;
    background-clip: text;
}

.hero-sub {
    font-size: 1rem;
    color: #64748b;
    line-height: 1.8;
    max-width: 420px;
    margin-bottom: 2.5rem;
}

.hero-cta {
    display: flex; flex-wrap: wrap; gap: 0.875rem; align-items: center;
}

.btn-prime {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 13px 26px;
    border-radius: 10px;
    font-weight: 700; font-size: 0.9rem;
    font-family: 'Sora', sans-serif;
    color: #fff;
    transition: transform 0.15s, box-shadow 0.15s;
    text-decoration: none;
    line-height: 1;
}
.btn-prime:hover{ transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,.18); }

.btn-wa {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 13px 22px;
    border-radius: 10px;
    font-weight: 600; font-size: 0.88rem;
    color: #1a3a2a;
    background: #dcfce7;
    border: 1.5px solid #bbf7d0;
    transition: background 0.15s, border-color 0.15s;
    text-decoration: none; line-height: 1;
}
.btn-wa:hover{ background: #bbf7d0; border-color: #86efac; }

.hero-stats {
    display: flex; gap: 2rem; flex-wrap: wrap;
    margin-top: 3rem; padding-top: 2.5rem;
    border-top: 1px solid #f1f5f9;
}
.hs-item strong {
    display: block;
    font-size: 1.85rem; font-weight: 800;
    letter-spacing: -0.04em; line-height: 1;
    font-family: 'Sora', sans-serif;
}
.hs-item span {
    font-size: 0.72rem; color: #94a3b8;
    font-weight: 600; margin-top: 4px; display: block;
    text-transform: uppercase; letter-spacing: 0.06em;
}

/* ── ARMADA SECTION ── */
.armada-section {
    padding: 5.5rem 0;
    background: #f8fafc;
}
.sec-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;
}
.sec-kicker {
    font-size: 0.68rem; font-weight: 800;
    letter-spacing: 0.14em; text-transform: uppercase;
    color: #94a3b8; margin-bottom: 0.5rem;
    display: flex; align-items: center; gap: 8px;
}
.sec-kicker::before {
    content: ''; width: 20px; height: 2px;
    background: currentColor; display: inline-block;
}
.sec-h2 {
    font-size: clamp(1.5rem, 2.8vw, 2rem);
    font-weight: 800; letter-spacing: -0.03em;
    color: #0f172a; line-height: 1.2;
    font-family: 'Sora', sans-serif;
}
.sec-link {
    font-size: 0.82rem; font-weight: 700;
    font-family: 'Sora', sans-serif;
    text-decoration: none; display: flex; align-items: center; gap: 4px;
    white-space: nowrap; transition: gap 0.15s;
}
.sec-link:hover{ gap: 8px; }

.armada-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
@media(max-width:1024px){ .armada-grid{grid-template-columns:repeat(2,1fr);} }
@media(max-width:580px){ .armada-grid{grid-template-columns:1fr;} }

.a-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    transition: box-shadow 0.2s, transform 0.2s;
    text-decoration: none; display: block; color: inherit;
    position: relative;
}
.a-card:hover { box-shadow: 0 16px 40px rgba(0,0,0,.09); transform: translateY(-3px); }

.a-card-img { width: 100%; height: 195px; object-fit: cover; display: block; }
.a-card-img-ph {
    width: 100%; height: 195px; background: #f1f5f9;
    display: flex; align-items: center; justify-content: center;
}
.a-card-body { padding: 1.1rem 1.25rem 1.4rem; }
.a-card-cat {
    font-size: 0.65rem; font-weight: 800;
    letter-spacing: 0.1em; text-transform: uppercase;
    margin-bottom: 0.3rem;
}
.a-card-name {
    font-size: 1rem; font-weight: 800;
    color: #0f172a; margin-bottom: 0.3rem;
    letter-spacing: -0.02em; font-family: 'Sora', sans-serif;
}
.a-card-meta { font-size: 0.78rem; color: #94a3b8; margin-bottom: 1.1rem; }
.a-card-foot {
    display: flex; align-items: flex-end; justify-content: space-between;
    border-top: 1px solid #f1f5f9; padding-top: 1rem;
}
.a-card-price { font-size: 1.15rem; font-weight: 800; letter-spacing: -0.03em; font-family: 'Sora', sans-serif; }
.a-card-price small { font-size: 0.68rem; font-weight: 500; color: #94a3b8; }
.a-card-label { font-size: 0.68rem; color: #94a3b8; margin-bottom: 2px; }
.a-card-btn {
    font-size: 0.78rem; font-weight: 700;
    padding: 8px 16px; border-radius: 8px;
    color: #fff; text-decoration: none; font-family: 'Sora', sans-serif;
    transition: opacity 0.15s;
}
.a-card-btn:hover{ opacity: 0.85; }

/* ── SPLIT INFO SECTION ── */
.split-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 480px;
}
@media(max-width:768px){ .split-section{grid-template-columns:1fr;} .split-img{min-height:260px;} }

.split-img { overflow: hidden; position: relative; }
.split-img img { width: 100%; height: 100%; object-fit: cover; }
.split-img-ph {
    width: 100%; height: 100%; min-height: 320px;
    background: #e2e8f0; display: flex; align-items: center; justify-content: center;
}
.split-content {
    padding: 4.5rem 3.5rem;
    display: flex; flex-direction: column; justify-content: center;
    background: #0f172a;
}
@media(max-width:900px){ .split-content{ padding: 3rem 2rem; } }

.split-step {
    display: flex; align-items: flex-start; gap: 1.25rem;
    margin-bottom: 2rem;
}
.split-step:last-child { margin-bottom: 0; }
.ss-num {
    flex-shrink: 0;
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 800;
    font-family: 'Sora', sans-serif; color: #fff;
    margin-top: 2px;
}
.ss-title {
    font-size: 0.95rem; font-weight: 700;
    color: #f1f5f9; margin-bottom: 0.3rem;
    font-family: 'Sora', sans-serif;
}
.ss-desc { font-size: 0.82rem; color: #94a3b8; line-height: 1.7; }

/* ── KEUNGGULAN ── */
.unggulan-section { padding: 5.5rem 0; background: #fff; }
.unggulan-grid {
    display: grid;
    grid-template-columns: 1.2fr 2.8fr;
    gap: 4rem;
    align-items: start;
}
@media(max-width:900px){ .unggulan-grid{grid-template-columns:1fr; gap:2rem;} }

.ug-cards {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
}
@media(max-width:500px){ .ug-cards{grid-template-columns:1fr;} }

.ug-card {
    padding: 1.5rem 1.25rem;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #fafafa;
}
.ug-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 0.875rem;
}
.ug-title {
    font-size: 0.88rem; font-weight: 700;
    color: #0f172a; margin-bottom: 0.35rem;
    font-family: 'Sora', sans-serif;
}
.ug-desc { font-size: 0.78rem; color: #64748b; line-height: 1.65; }

/* ── CTA ── */
.cta-section { padding: 5rem 0; background: #f8fafc; }
.cta-inner {
    border-radius: 22px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 320px;
}
@media(max-width:768px){ .cta-inner{grid-template-columns:1fr;} }

.cta-text {
    padding: 3.5rem 3rem;
    display: flex; flex-direction: column; justify-content: center;
    position: relative; overflow: hidden;
}
.cta-text::before {
    content: '';
    position: absolute; top: -80px; right: -80px;
    width: 240px; height: 240px; border-radius: 50%;
    background: rgba(255,255,255,0.08);
}
.cta-text h2 {
    font-size: clamp(1.4rem,2.5vw,1.9rem);
    font-weight: 800; color: #fff;
    letter-spacing: -0.03em; line-height: 1.25;
    margin-bottom: 0.875rem;
    font-family: 'Sora', sans-serif;
}
.cta-text p { font-size: 0.88rem; color: rgba(255,255,255,.7); line-height: 1.75; max-width: 340px; }

.cta-action {
    padding: 3rem 2.5rem;
    background: #fff;
    display: flex; flex-direction: column; justify-content: center; gap: 1rem;
}
@media(max-width:900px){ .cta-text{ padding: 3rem 2rem; } .cta-action{ padding: 2.5rem 2rem; } }

.cta-action-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700; font-size: 0.9rem;
    font-family: 'Sora', sans-serif;
    transition: transform 0.15s, box-shadow 0.15s;
    border: 1.5px solid transparent;
}
.cta-action-btn:hover{ transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,0,0,.1); }
.cta-action-btn .cab-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.cta-action-btn .cab-text span { display: block; font-size: 0.7rem; color: #94a3b8; font-weight: 500; margin-bottom: 1px; }
.cta-action-btn .cab-text strong { font-size: 0.9rem; color: #0f172a; }
</style>

<div class="hw">

<!-- ═══ HERO ═══ -->
<section class="hero">
    <div class="hero-left">
        <p class="hero-eyebrow">
            <span></span>
            Rental Kendaraan Terpercaya
        </p>

        <h1 class="hero-h1">
            Butuh Kendaraan?<br>
            <mark style="background-image: linear-gradient(135deg, <?= e($primaryColor) ?>, <?= e($primaryColor) ?>bb)">
                Kami Siap Antar.
            </mark>
        </h1>

        <p class="hero-sub">
            <?= e($s['tagline'] ?? 'Sewa mobil dan motor dengan mudah. Booking via WhatsApp, bayar di tempat — tanpa ribet, tanpa daftar akun.') ?>
        </p>

        <div class="hero-cta">
            <a href="/kendaraan" class="btn-prime" style="background:<?= e($primaryColor) ?>">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4"/>
                </svg>
                Cek Armada
            </a>
            <?php if (!empty($s['whatsapp_number'])): ?>
            <a href="https://wa.me/<?= e($s['whatsapp_number']) ?>" target="_blank" class="btn-wa">
                <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Tanya via WhatsApp
            </a>
            <?php endif; ?>
        </div>

        <div class="hero-stats">
            <?php foreach([['100+','Unit Armada'],['24 Jam','Siap Melayani'],['1000+','Pelanggan']] as $st): ?>
            <div class="hs-item">
                <strong style="color:<?= e($primaryColor) ?>"><?= $st[0] ?></strong>
                <span><?= $st[1] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="hero-right">
        <div class="hero-right-placeholder">
            <!-- Ganti dengan: <img src="foto-armada.jpg" alt="Armada"> -->
            <svg width="100" height="100" fill="none" viewBox="0 0 24 24" stroke="#c8d3e0" stroke-width="0.7">
                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/>
            </svg>
        </div>
    </div>
</section>

<!-- ═══ ARMADA ═══ -->
<section class="armada-section">
    <div style="max-width:1200px;margin:0 auto;padding:0 1.5rem;">
        <div class="sec-header">
            <div>
                <p class="sec-kicker">Pilihan Kendaraan</p>
                <h2 class="sec-h2">Armada Kami</h2>
            </div>
            <a href="/kendaraan" class="sec-link" style="color:<?= e($primaryColor) ?>">
                Lihat Semua
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="armada-grid">
            <?php foreach($featuredVehicles as $v): ?>
            <a href="/kendaraan/<?= e($v['slug']) ?>" class="a-card">
                <?php if(!empty($v['primary_image'])): ?>
                    <img src="<?= e($v['primary_image']) ?>" alt="<?= e($v['vehicle_name']) ?>" class="a-card-img" loading="lazy">
                <?php else: ?>
                    <div class="a-card-img-ph">
                        <svg width="52" height="52" fill="none" viewBox="0 0 24 24" stroke="#c8d3e0" stroke-width="0.9">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <div class="a-card-body">
                    <p class="a-card-cat" style="color:<?= e($primaryColor) ?>"><?= ucfirst(e($v['vehicle_type'])) ?></p>
                    <p class="a-card-name"><?= e($v['vehicle_name']) ?></p>
                    <p class="a-card-meta">
                        <?= $v['transmission']==='otomatis'?'Matic':'Manual' ?>
                        <?php if(!empty($v['passenger_capacity'])): ?> &middot; <?= e($v['passenger_capacity']) ?> kursi<?php endif; ?>
                        <?php if(!empty($v['brand'])): ?> &middot; <?= e($v['brand']) ?><?php endif; ?>
                    </p>
                    <div class="a-card-foot">
                        <div>
                            <p class="a-card-label">Mulai dari</p>
                            <p class="a-card-price" style="color:<?= e($primaryColor) ?>">
                                <?= format_rupiah($v['price_per_day']) ?> <small>/hari</small>
                            </p>
                        </div>
                        <span class="a-card-btn" style="background:<?= e($primaryColor) ?>">Pesan</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
            <?php if(empty($featuredVehicles)): ?>
            <div style="grid-column:1/-1;text-align:center;padding:4rem;color:#94a3b8;font-size:.9rem;">
                Belum ada kendaraan tersedia.
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ CARA BOOKING (SPLIT) ═══ -->
<section class="split-section" id="cara-booking">
    <div class="split-img">
        <div class="split-img-ph">
            <svg width="80" height="80" fill="none" viewBox="0 0 24 24" stroke="#c8d3e0" stroke-width="0.8">
                <path d="M8 12h.01M12 12h.01M16 12h.01M21 3H3a1 1 0 00-1 1v14a1 1 0 001 1h5l3 3 3-3h5a1 1 0 001-1V4a1 1 0 00-1-1z"/>
            </svg>
        </div>
        <!-- Untuk pakai foto: ganti div di atas dengan <img src="foto-wa.jpg" class="..." style="width:100%;height:100%;object-fit:cover"> -->
    </div>
    <div class="split-content">
        <p class="sec-kicker" style="color:#475569;margin-bottom:1.25rem;">Proses Booking</p>
        <h2 style="font-size:clamp(1.4rem,2.2vw,1.8rem);font-weight:800;color:#f8fafc;letter-spacing:-0.03em;margin-bottom:2rem;font-family:'Sora',sans-serif;line-height:1.25;">
            Mudah & Cepat,<br>Tanpa Daftar Akun
        </h2>
        <?php foreach([
            ['01','Pilih Kendaraan','Browse armada kami, pilih yang sesuai kebutuhan dan tanggal sewa Anda.'],
            ['02','Isi Form Booking','Isi nama, nomor HP, dan tanggal. Total biaya langsung terhitung otomatis.'],
            ['03','Konfirmasi WA','Kami hubungi via WhatsApp untuk konfirmasi. Bayar saat ambil kendaraan.'],
        ] as $step): ?>
        <div class="split-step">
            <div class="ss-num" style="background:<?= e($primaryColor) ?>"><?= $step[0] ?></div>
            <div>
                <p class="ss-title"><?= $step[1] ?></p>
                <p class="ss-desc"><?= $step[2] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(!empty($s['whatsapp_number'])): ?>
        <div style="margin-top:2rem;">
            <a href="https://wa.me/<?= e($s['whatsapp_number']) ?>" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 22px;background:#22c55e;color:#fff;border-radius:10px;font-weight:700;font-size:.88rem;font-family:'Sora',sans-serif;text-decoration:none;transition:opacity .15s;"
               onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <svg width="17" height="17" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Mulai Booking via WhatsApp
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══ KEUNGGULAN ═══ -->
<section class="unggulan-section">
    <div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;">
        <div class="unggulan-grid">
            <div>
                <p class="sec-kicker">Keunggulan Kami</p>
                <h2 class="sec-h2" style="margin-bottom:1rem;">Kenapa<br>Pilih Kami?</h2>
                <p style="font-size:.875rem;color:#64748b;line-height:1.8;margin-bottom:1.5rem;">
                    Bukan sekadar rental. Kami pastikan kendaraan sampai ke tangan Anda dalam kondisi prima dan proses yang tidak menyusahkan.
                </p>
                <a href="/kendaraan" class="btn-prime" style="background:<?= e($primaryColor) ?>;font-size:.85rem;padding:11px 20px;">
                    Lihat Armada
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="ug-cards">
                <?php foreach([
                    ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Kendaraan Prima','Servis rutin, selalu bersih dan layak jalan setiap saat.'],
                    ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','Respon Cepat','Konfirmasi booking dalam menit, bukan jam.'],
                    ['M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z','Harga Transparan','Tidak ada biaya tersembunyi. Harga tertera adalah harga final.'],
                    ['M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','Support 24 Jam','Tim kami siap membantu kapanpun Anda butuhkan.'],
                ] as $f): ?>
                <div class="ug-card">
                    <div class="ug-icon" style="background:<?= e($primaryColor) ?>15">
                        <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:<?= e($primaryColor) ?>">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $f[0] ?>"/>
                        </svg>
                    </div>
                    <h3 class="ug-title"><?= $f[1] ?></h3>
                    <p class="ug-desc"><?= $f[2] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA ═══ -->
<section class="cta-section">
    <div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;">
        <div class="cta-inner">
            <div class="cta-text" style="background:<?= e($primaryColor) ?>">
                <h2>Siap Berangkat?<br>Booking Sekarang.</h2>
                <p>Proses mudah, tidak ribet. Kendaraan antar-jemput sesuai jadwal Anda. Bayar langsung di tempat.</p>
            </div>
            <div class="cta-action">
                <a href="/kendaraan" class="cta-action-btn" style="border-color:<?= e($primaryColor) ?>20;background:<?= e($primaryColor) ?>08">
                    <div class="cab-icon" style="background:<?= e($primaryColor) ?>15">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:<?= e($primaryColor) ?>">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M13 16V6a1 1 0 00-1-1H4"/>
                        </svg>
                    </div>
                    <div class="cab-text">
                        <span>Lihat pilihan</span>
                        <strong>Cek Semua Kendaraan</strong>
                    </div>
                </a>
                <?php if(!empty($s['whatsapp_number'])): ?>
                <a href="https://wa.me/<?= e($s['whatsapp_number']) ?>" target="_blank"
                   class="cta-action-btn" style="border-color:#bbf7d0;background:#f0fdf4;">
                    <div class="cab-icon" style="background:#dcfce7">
                        <svg width="18" height="18" fill="#16a34a" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div class="cab-text">
                        <span>Chat langsung</span>
                        <strong style="color:#166534;">Hubungi WhatsApp</strong>
                    </div>
                </a>
                <?php endif; ?>
                <p style="font-size:.72rem;color:#94a3b8;text-align:center;margin-top:.25rem;">
                    Respon cepat &middot; Tidak perlu daftar akun
                </p>
            </div>
        </div>
    </div>
</section>

</div><!-- .hw -->

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
