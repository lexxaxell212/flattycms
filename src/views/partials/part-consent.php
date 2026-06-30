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
<div id="consentBanner" class="mx-auto consent-banner">
    <button type="button" class="btn bg-surface btn-fit position-absolute top-0 end-0 m-3" onclick="dismissBanner()" aria-label="Close"><i class="fas fa-xmark"></i></button>
    <h3 class="h4">Kami Menghargai Privasi Anda</h3>
    <p class="mb-4 small">
        Kami menggunakan cookie untuk meningkatkan pengalaman browsing anda.
        Dengan mengklik <strong>Terima</strong>, anda menyetujui 
        penggunaan cookie sesuai <a href="/privacy-policy">Kebijakan Privasi</a> kami.
    </p>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-primary btn-fit" onclick="saveConsent(true)">Terima</button>
        <button class="btn btn-outline-primary btn-fit" onclick="saveConsent(false, true)">
          Tolak</button>
    </div>
</div>
<?php endif; ?>