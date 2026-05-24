<?php
$consent_accepted = ($_COOKIE["consent_accepted"] ?? "0") === "1";
$categories = json_decode($_COOKIE["consent_categories"] ?? '[]', true);

if ($consent_accepted): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= G_TAG_ID ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date()); gtag('config', '<?= G_TAG_ID ?>');
    </script>

    <?php if ($categories['marketing'] ?? false): ?>
        <script>
            !function(f,b,e,v,n,t,s){/* Script FB Pixel Standard */}...
            fbq('init', '<?= FB_PIXEL_ID ?>'); fbq('track', 'PageView');
        </script>
    <?php endif; ?>
<?php endif; ?>

<?php if (!$consent_accepted): ?>

<link rel="stylesheet" href="<?= CSS_URL ?>consent.css">

<script src="<?= JS_URL ?>consent.js" defer></script>

<div id="consentBanner" class="consent-banner show">
    <h5 class="fw-bold mb-2">Kami Menghargai Privasi Anda</h5>
    <p class="small text-muted mb-3">
        Kami menggunakan cookie untuk meningkatkan pengalaman browsing anda, 
        menampilkan konten yang relevan, dan menganalisis traffic situs. 
        Dengan mengklik <strong>Terima Semua</strong>, anda menyetujui 
        penggunaan cookie sesuai <a href="/privacy-policy" class="text-primary">Kebijakan Privasi</a> kami.
        anda dapat mengatur preferensi kapan saja.
    </p>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-primary btn-sm" onclick="saveConsent(true)">Terima Semua</button>
        <button class="btn btn-outline-secondary btn-sm" onclick="openPreferences()">Atur Sendiri</button>
        <button class="btn btn-link btn-sm text-muted" onclick="saveConsent(false, true)">Tolak Semua</button>
    </div>
</div>
<?php endif; ?>

<div id="prefModal" style="display:none;" class="modal">
    <div class="modal-dialog">
        <div class="modal-content p-4">
            <h5 class="fw-bold mb-3">Preferensi Cookie</h5>
            <p class="small text-muted mb-3">
                Pilih jenis cookie yang anda izinkan. Cookie yang diperlukan 
                selalu aktif karena dibutuhkan untuk fungsi dasar situs.
            </p>
            <div class="mb-3">
                <label class="d-flex align-items-center gap-2">
                    <input type="checkbox" checked disabled>
                    <div>
                        <strong class="d-block">Diperlukan</strong>
                        <span class="small text-muted">Selalu aktif — dibutuhkan untuk fungsi dasar situs.</span>
                    </div>
                </label>
            </div>
            <div class="mb-3">
                <label class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="check_analytics">
                    <div>
                        <strong class="d-block">Analitik</strong>
                        <span class="small text-muted">Membantu kami memahami cara pengunjung menggunakan situs.</span>
                    </div>
                </label>
            </div>
            <div class="mb-3">
                <label class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="check_marketing">
                    <div>
                        <strong class="d-block">Marketing</strong>
                        <span class="small text-muted">Digunakan untuk menampilkan iklan yang relevan untukmu.</span>
                    </div>
                </label>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm" onclick="saveConsent(false)">Simpan Preferensi</button>
                <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('prefModal').style.display='none'">Batal</button>
            </div>
        </div>
    </div>
</div>