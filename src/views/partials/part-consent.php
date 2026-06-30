<?php
$consent_accepted = ($_COOKIE["consent_accepted"] ?? "0") === "1";
$categories = json_decode($_COOKIE["consent_categories"] ?? "[]", true);
?>

<?php if ($consent_accepted && defined('G_TAG_ID') && G_TAG_ID): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= G_TAG_ID ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date()); gtag('config', '<?= G_TAG_ID ?>');
    </script>
<?php endif; ?>

<?php if ($consent_accepted && ($categories["marketing"] ?? false) && defined('FB_PIXEL_ID') && FB_PIXEL_ID): ?>
    <script>
        !function(f,b,e,v,n,t,s){
          if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)
        }(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= FB_PIXEL_ID ?>');
        fbq('track', 'PageView');
    </script>
<?php endif; ?>

<?php if (!$consent_accepted): ?>
<script>
    const GTAG_ID = "<?= defined('G_TAG_ID') ? G_TAG_ID : '' ?>";
    const FB_PIXEL_ID = "<?= defined('FB_PIXEL_ID') ? FB_PIXEL_ID : '' ?>";
</script>
<script src="<?= JS_URL ?>consent.js" defer></script>
<div id="consentBanner" class="mx-auto consent-banner">
  <div class="consent-header">
    <span class="text-white fw-bold">Kami Menghargai Privasi Anda</span>
    <button type="button" id="btnDismissConsent" class="btn-close p-0 btn btn-sm btn-fit rounded-full shadow-none" aria-label="Close">
      <i class="fas fa-xmark fs-5"></i>
    </button>
  </div>
  <div class="consent-body">
    <p class="small fw-medium mb-4">
      Kami menggunakan cookie untuk meningkatkan pengalaman browsing anda. Dengan mengklik <strong>Terima</strong>, anda menyetujui penggunaan cookie sesuai <a href="/privacy-policy">Kebijakan Privasi</a> kami.
    </p>
    <div class="d-flex gap-2 flex-wrap">
      <button id="btnAcceptConsent" class="btn btn-primary btn-fit">Terima</button>
      <button id="btnRejectConsent" class="btn btn-outline-primary btn-fit">Tolak</button>
    </div>
  </div>
</div>
<?php endif; ?>