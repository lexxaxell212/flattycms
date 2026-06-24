<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActive(string $path): string {
  global $currentPath;
  $normalizedCurrent = ($currentPath === '' || $currentPath === '/') ? '/' : rtrim($currentPath, '/');
  $normalizedTarget = ($path === '' || $path === '/') ? '/' : rtrim($path, '/');
  if ($normalizedTarget === '/') {
    return ($normalizedCurrent === '/') ? 'is-current' : '';
  }
  return str_starts_with($normalizedCurrent, $normalizedTarget) ? 'is-current' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#ffffff">
  <link rel="canonical" href="<?= BASE_URL ?>">
  <?php $s = $GLOBALS['site_settings'] ?? []; ?>
  <title><?= $show_title ?></title>
  <meta name="description" content="<?= safe_html($s['site_description'] ?? 'Eksplorasi Wisata, Kuliner, dan Budaya Bandung Terlengkap.') ?>">
  <meta property="og:description" content="<?= safe_html($s['site_tagline'] ?? '') ?>">
  <link rel="icon" href="<?= !empty($s['favicon_url']) ? safe_html($s['favicon_url']) : IMG_URL . 'favicon.ico' ?>" type="image/x-icon">
  <?php if (!empty($s['meta_keywords'])): ?>
  <meta name="keywords" content="<?= safe_html($s['meta_keywords']) ?>">
  <?php endif; ?>
  <?php if (!empty($s['og_image'])): ?>
  <meta property="og:image" content="<?= safe_html($s['og_image']) ?>">
  <?php endif; ?>
  <?php if (!empty($s['twitter_handle'])): ?>
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="<?= safe_html($s['twitter_handle']) ?>">
  <?php if (!empty($s['og_image'])): ?>
  <meta name="twitter:image" content="<?= safe_html($s['og_image']) ?>">
  <?php endif; ?>
  <?php endif; ?>
  <?php if (!empty($s['gtag_id'])): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= safe_html($s['gtag_id']) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', '<?= safe_html($s['gtag_id']) ?>');
  </script>
  <?php endif; ?>
  <?php if (!empty($s['fb_pixel_id'])): ?>
  <script>
    !function(f, b, e, v, n, t, s) {
      if (f.fbq)return; n = f.fbq = function() {
        n.callMethod?
        n.callMethod.apply(n, arguments): n.queue.push(arguments)}; if (!f._fbq)f._fbq = n;
      n.push = n; n.loaded=!0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async=!0;
      t.src = v; s = b.getElementsByTagName(e)[0]; s.getElementsByTagName.insertBefore(t, s);
    }
    (window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '<?= safe_html($s['fb_pixel_id']) ?>');
    fbq('track', 'PageView');
  </script>
  <?php endif; ?>
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?= safe_html($s['site_name'] ?? SITE_NAME) ?>",
      "url": "<?= BASE_URL ?>",
      "email": "<?= safe_html($s['contact_email'] ?? '') ?>",
      "telephone": "<?= safe_html($s['contact_wa'] ?? '') ?>",
      "sameAs": [
        "<?= safe_html($s['social_instagram'] ?? '') ?>",
        "<?= safe_html($s['social_facebook'] ?? '') ?>",
        "<?= safe_html($s['social_tiktok'] ?? '') ?>"
      ]
    }
  </script>
  <!-- assets -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= CSS_URL ?>fa720.all.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>bs538.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>flatty1.1.min.css">
  <?php
  $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);
  $heroImg = $isMobile ? 'wisata-mobile.webp' : 'wisata.webp'; ?>
  <link rel="preload" as="image" href="<?= IMG_URL . $heroImg ?>" type="image/webp" fetchpriority="high">
  <!-- script -->
  <script src="https://accounts.google.com/gsi/client"></script>
  <script src="<?= JS_URL ?>gsi.js"></script>
  <script src="<?= JS_URL ?>bs538.bundle.min.js" defer></script>
  <script src="<?= JS_URL ?>flattynotif.js" defer></script>
  <script src="<?= JS_URL ?>main.js" defer></script>
  <script src="<?= JS_URL ?>lang.js" defer></script>
  <script>
    const CONFIG = {
      baseUrl: '<?= BASE_URL ?>',
      isLoggedIn: <?= !empty($_SESSION['user']) ? 'true' : 'false' ?>,
      csrfToken: '<?= generate_csrf_token() ?>',
    };
  </script>
  <?php if (!empty($_SESSION['flash'])): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      flattyToast('<?= $_SESSION['flash']['type'] ?>', '<?= addslashes($_SESSION['flash']['message']) ?>');
    });
  </script>
  <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>
  <style>
    body {
      visibility: hidden;
    }

    #page-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 40%;
      height: 1px;
      background: #7c3aed;
      z-index: 9999;
      transition: width 0.3s ease,opacity 0.3s ease;
    }
  </style>
</head>
<body>
  <div id="page-loader"></div>
  <div id="flatty-container-top-end"></div>
  <div id="flatty-container-bottom"></div>
  <button id="scrollTopBtn" class="fab scroll-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>
  <?php
  require_once SRC_PATH . "partials/ui/navbar.php";
  require_once SRC_PATH . "partials/ui/livesearch.php";
  require_once SRC_PATH . "partials/ui/chatbot.php";
  ?>