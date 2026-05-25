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

<script src="<?= JS_URL ?>consent.js" defer></script>

<div id="consentBanner" class="mx-auto consent-banner show">
    <h4>Kami Menghargai Privasi Anda</h4>
    <p class="small text-muted mb-3">
        Kami menggunakan cookie untuk meningkatkan pengalaman browsing anda, 
        menampilkan konten yang relevan, dan menganalisis traffic situs. 
        Dengan mengklik <strong>Terima Semua</strong>, anda menyetujui 
        penggunaan cookie sesuai <a href="/privacy-policy">Kebijakan Privasi</a> kami.
        anda dapat mengatur preferensi kapan saja.
    </p>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-primary" onclick="saveConsent(true)">Terima Semua</button>
        <button class="btn btn-outline-primary" onclick="saveConsent(false, true)">Tolak Semua</button>
    </div>
</div>
<?php endif; ?>
  