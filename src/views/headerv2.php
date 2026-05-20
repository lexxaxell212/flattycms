<?php
$page_title = htmlspecialchars($_POST["title"] ?? ($page_title ?? SITE_NAME));

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActive(string $path): string {
    global $currentPath;
    return str_starts_with($currentPath, $path) ? 'is-current' : '';
}
// Dropdown
$pintasanPaths = [
    '/p/sejarah',
    '/p/budaya', 
    '/p/kuliner',
    '/p/layanan',
    '/p/wisata',
    '/p/penginapan'
];
$isPintasanActive = (bool) array_filter(
    $pintasanPaths, 
    fn($p) => str_starts_with($currentPath, $p)
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#ffffff">
  <link rel="canonical" href="<?= BASE_URL ?>">
  <!-- assets -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= CSS_URL ?>bs533.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>flattypurple.css">
  <?php
  $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);
  $heroImg = $isMobile ? 'wisata-mobile.webp' : 'wisata.webp'; ?>
  <link rel="preload" as="image" href="<?= IMG_URL . $heroImg ?>" type="image/webp" fetchpriority="high">
  <link rel="preload" as="style" href="<?= CSS_URL ?>flattyui.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>flattyui.css" media="print" onload="this.media='all'">
  <link rel="preload" as="style" href="<?= CSS_URL ?>fa651.all.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css" media="print" onload="this.media='all'">
  <noscript>
    <link rel="stylesheet" href="<?= CSS_URL ?>flattyui.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css">
  </noscript>
  <script>
    const CONFIG = {
        baseUrl: '<?= BASE_URL ?>',
        isLoggedIn: <?= isset($_SESSION['user']) ? 'true' : 'false' ?>,
        csrfToken: '<?= generate_csrf_token() ?>',
    };
  </script>
  <script src="<?= JS_URL ?>bs533.bundle.min.js" defer></script>
  <script src="<?= JS_URL ?>swal2.all.min.js" defer></script>
  <script src="<?= JS_URL ?>chat.js" defer></script>
  <script src="<?= JS_URL ?>live-search.js" defer></script>
  <script src="<?= JS_URL ?>navbar.js" defer></script>
  <script src="<?= JS_URL ?>weather.js" defer></script>
  <script src="<?= JS_URL ?>newsletter-form.js" defer></script>
  <script src="<?= JS_URL ?>reactions.js" defer></script>
  <?php 
  $s = $GLOBALS['site_settings'] ?? []; 
  ?>
  <title><?= $page_title ?></title>
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
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= safe_html($s['gtag_id']) ?>');
  </script>
  <?php endif; ?>
  <?php if (!empty($s['fb_pixel_id'])): ?>
  <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.getElementsByTagName.insertBefore(t,s);}
      (window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
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
  
   <script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      background: '#f6f5ff',
      color: '#3b0764',
      iconColor: '#6c2bd9',
    });
  </script>
  
  <?php if (!empty($_SESSION['flash'])): ?>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: '<?= $_SESSION['flash']['type'] ?>',
      title: '<?= addslashes($_SESSION['flash']['message']) ?>',
      showConfirmButton: false,
      timer: 3000
    });
  });
  </script>
  <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>
  
</head>
<body>
<style>
.nav-login-btn {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 7px 14px;
  border-radius: 10px;
  background: var(--glass-bg);
  color: var(--text-nav);
  font-size: var(--space-4);
  font-weight: 500;
  text-decoration: none;
  white-space: nowrap;
  transition: background 0.2s ease, color 0.2s ease, transform 0.15s ease;
}
.nav-login-btn:hover {
  background: var(--glass-bg-hover);
  color: var(--text-nav-hover);
  transform: translateY(-1px);
}
.nav-login-btn--full {
  width: 100%;
  justify-content: center;
  padding: 11px 14px;
  margin-top: 4px;
}

.nav-user-wrap {
  position: relative;
}
.nav-user-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  border-radius: 50%;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.nav-user-btn:hover {
  transform: scale(1.06);
  box-shadow: 0 0 0 3px rgba(37, 99, 176, 0.15);
  border-radius: 50%;
}
.nav-user-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  border: 2px solid var(--glass-border);
}
.nav-user-avatar-lg {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  border: 2px solid var(--glass-border);
  flex-shrink: 0;
}

.nav-user-panel {
  position: absolute;
  top: calc(100% + 12px);
  right: 0;
  min-width: 220px;
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius);
  box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
  padding: 8px;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transform: translateY(-6px) scale(0.98);
  transform-origin: top right;
  z-index: 1080;
  transition:
    opacity 0.2s ease,
    transform 0.2s ease,
    visibility 0s linear 0.2s;
}
.nav-user-wrap.dd-open .nav-user-panel,
.nav-user-wrap:hover .nav-user-panel {
  opacity: 1;
  visibility: visible;
  pointer-events: all;
  transform: translateY(0) scale(1);
  transition:
    opacity 0.2s ease,
    transform 0.2s ease,
    visibility 0s linear 0s;
}
[data-dark] .nav-user-panel {
  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.50),
    0 4px 16px rgba(0, 0, 0, 0.30);
}

.nav-user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px;
  border-radius: 10px;
}
.nav-user-meta {
  display: flex;
  flex-direction: column;
  gap: 2px;
  overflow: hidden;
}
.nav-user-name {
  font-size: 13.5px;
  font-weight: 600;
  color: var(--pu-900);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.nav-user-email {
  font-size: 11.5px;
  color: var(--pu-900);
  opacity: 0.55;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.nav-user-divider {
  height: 1px;
  background: var(--glass-border);
  margin: 6px 4px;
}

.nav-user-logout {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 9px 12px;
  border-radius: 10px;
  font-size: 13.5px;
  font-weight: 500;
  color: #e53e3e;
  text-decoration: none;
  transition: background 0.15s ease, transform 0.15s ease;
  width: 100%;
}
.nav-user-logout:hover {
  background: rgba(229, 62, 62, 0.08);
  transform: translateX(2px);
  color: #e53e3e;
}
.nav-user-logout i {
  font-size: 13px;
  opacity: 0.85;
}

.mobile-user-section {
  border-top: 1px solid var(--glass-border);
  margin-top: 8px;
  padding-top: 10px;
  padding-bottom: 4px;
}
.mobile-user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px;
  border-radius: 10px;
  margin-bottom: 4px;
}

@media (max-width: 991px) {
  .nav-user-wrap,
  .nav-login-btn:not(.nav-login-btn--full) {
    display: none !important;
  }
}
@media (min-width: 992px) {
  .mobile-user-section {
    display: none !important;
  }
}
</style>
<nav class="navbar">
  <div class="container">
    <a aria-label="Halaman awal" href="<?= BASE_URL ?>">
      <div class="navbar-brand">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="1644 484 1810 335"><path
fill="var(--ayoke)" fill-opacity=".3" d="M2078.3 533.7c4.8.2 12.6.2 17.5 0
4.8-.1.8-.3-8.8-.3s-13.6.2-8.7.3"/><path fill="var(--ayoke)" fill-opacity=".4"
d="M2078.3 484.7c4.8.2 12.5.2 17 0 4.5-.1.6-.3-8.8-.3-9.3 0-13 .2-8.2.3"/><path
fill="var(--ayoke)" fill-opacity=".8" d="M2078.3 532.7c4.8.2 12.6.2 17.5 0
4.8-.1.8-.3-8.8-.3s-13.6.2-8.7.3"/><path fill="var(--ayoke)" d="M1870.9 504.2c-3.4
10.6-6.8 21.2-7.5 23.5l-1.3 4.3h11.7l1.1-4.5 1.1-4.5h17l1.2 4.5 1.2
4.5h13.4l-1.3-4.3c-.7-2.3-4-12.9-7.4-23.5l-6.2-19.2h-16.7zm16.5-.8c3.6 11.7 3.7
11.6-2.9 11.6-6.2 0-6.2 0-4-7.5.9-2.8 2-6.7 2.6-8.8.5-2 1.2-3.7 1.4-3.7s1.5 3.8
2.9 8.4m20.6-17.6c0 .4 3.2 6.3 7 13.1l7 12.3V532h13v-19.9l7-12.8c3.8-7 7-13.1
7-13.6 0-.4-2.5-.7-5.5-.7h-5.5l-4.4 8.7-4.4 8.8-4.3-8.8-4.3-8.7h-6.3c-3.5
0-6.3.3-6.3.8m57.9.6c-5.6 2-9.6 5.3-12.6 10.4-3.7 6.3-4 17.4-.6 23.8 4.6 8.7
13.1 13 24 12.2 14.7-1.1 23.3-9.9
23.3-24-.1-10.9-5.2-18.7-14.7-22.1-5.6-2-14.3-2.2-19.4-.3m15.7 9.5c7.7 4.7 7
22.7-1.1 26.5-4.1 2-6.8 2-10.5.1-4.3-2.2-6-6.3-6-14.6 0-6.7.2-7.3 3.4-10.5 4-4
9.2-4.6 14.2-1.5m40.4 12.6V532h12.9l.3-10.2.3-10.1 6.8 10.1 6.7
10.2h14.1l-4.3-6.1c-2.4-3.4-5-7.1-5.8-8.3s-2.7-3.8-4.2-5.7c-1.6-2-2.8-4-2.8-4.6
0-.5 3.6-5.6 8-11.2 4.4-5.7 8-10.5 8-10.7s-2.6-.4-5.7-.4h-5.8l-7.5 9.8-7.5
9.7-.3-9.8-.3-9.7H2022zm49 0V532h33v-9h-21v-10h16v-7.9l-7.7-.3-7.8-.3v-10l9.8-.3
9.7-.3V485h-32z"/><path fill="var(--ayoke)" fill-opacity=".9" d="M2021.4 508.5c0
13.2.2 18.5.3 11.7.2-6.8.2-17.6 0-24-.1-6.4-.3-.9-.3 12.3m49 0c0 13.2.2 18.5.3
11.7.2-6.8.2-17.6 0-24-.1-6.4-.3-.9-.3 12.3"/><path fill="var(--bandung)"
fill-opacity=".1" d="M2375.3 494.7c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3m-659 58c4.3.2 11 .2 15 0 4-.1.5-.3-7.8-.3-8.2
0-11.5.2-7.2.3m792.2 68.3c0 28.3.1 39.9.2 25.7.2-14.1.2-37.3
0-51.5-.1-14.1-.2-2.5-.2 25.8m-654.6 6.3c.4.4 12.9.6
27.7.5l26.9-.3-27.7-.5c-15.2-.3-27.4-.2-26.9.3m-137.1 14.4c4.6.2 11.8.2 16 0
4.2-.1.5-.3-8.3-.3s-12.3.2-7.7.3"/><path fill-opacity=".1" d="M2620.4 625c0
11.3.2 15.9.3 10.2.2-5.6.2-14.8 0-20.5-.1-5.6-.3-1-.3 10.3m143.1 65c0 27.8.1
39.1.2 25.2.2-13.8.2-36.6 0-50.5-.1-13.8-.2-2.5-.2 25.3m-143 6.5c0 24.2.1 34 .2
21.7.2-12.3.2-32.1 0-44-.1-11.9-.2-1.9-.2 22.3m90 0c0 24.2.1 34 .2
21.7.2-12.3.2-32.1 0-44-.1-11.9-.2-1.9-.2 22.3"/><path fill="var(--bandung)"
fill-opacity=".6" d="M2375.3 495.7c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3m-666.9 56.6c-.2.7-.3 11.6-.1 24.2l.2 23
.3-23.7.2-23.7 15.8-.4 15.7-.3-15.8-.2c-12-.1-15.9.1-16.3 1.1m158.9 74.4c7.5.2
19.9.2 27.5 0 7.5-.1 1.3-.3-13.8-.3s-21.3.2-13.7.3m-158.8 14.6c-.3.7-.4 13.1-.2
27.7l.2 26.5.3-27.2.2-27.2 15.8-.4 15.7-.3-15.8-.2c-12-.1-15.9.1-16.2
1.1"/><path fill="var(--bandung)" d="M2360.9 545.7c0 47.2-.1 49.7-1.7
47.3-11.1-16.2-24.6-24.3-43.3-25.9-34-2.9-60.4 13.8-72.8 45.9-5.2 13.5-6.6
22.5-6.6 43 0 13.5.5 20.6 1.7 26.1 4.3 19.2 11.1 32.2 23.1 43.6 12.3 11.7 27.2
17.3 45.6 17.3 21.5 0 39.4-9.1 50.1-25.3l4-6.2V740h58V496h-58zm-16.6 66.3c11.5
5.8 17.1 17.5 18.3 38 1.5 25.3-6.4 43-21.7 48.6-6.8 2.5-20.3
2.4-26-.2-6.6-3.1-11.4-7.8-14.8-14.5-4.4-8.9-5.4-15.1-4.9-32.8.4-13.1.8-16.5
2.7-21.2 5.9-15.3 15.1-21.2 32.1-20.6 7.7.2 10.4.7 14.3 2.7M1644
623.9V740h50.3c58.8-.1 76.2-.9 89.9-4.4 31.3-8 47.1-26.3 48.5-56
1.4-30.6-11.7-49.7-39.3-57.2-3.5-.9-6.3-2.1-6.1-2.7.1-.5 3-2 6.4-3.2 22.1-8
33.9-27.1
31.9-52-1.1-15-6.1-26.8-15.2-35.7-10-9.8-20.6-14.8-39.4-18.5-5.9-1.2-20.2-1.6-67.2-2l-59.8-.5zm108.5-69.4c7.8
3.9 9.7 7.2 10.3 17.6.7 11.3-.4 16.3-4.1 20.6-5.9 6.8-9.7 7.7-31.4
8.1l-19.3.4v-50.4l19.3.4c19 .3 19.2.3 25.2 3.3m-5.3 86.6c11.1 2.4 16.6 7.1 19.4
16.7 2 6.9 1.4 19.2-1.1 24.3-6 11.8-14.7 14.9-41.7 14.9H1708v-57h17c9.3 0 19.3.5
22.2 1.1m177.3-74c-38.1 2.6-63.5 21.2-69.1 50.5-.8 4.2-1.4 7.8-1.4 8s12.1.4
26.9.4h27l1.5-5.3c2.9-10 10.1-14.6 22.7-14.7 15.5 0 21.4 6 22.6 23.5l.6
8.3-18.9.7c-31.1 1.1-45.4 3.7-60.1 10.6q-16.8 7.95-24 22.5c-3.3 6.8-3.7 8.4-4.1
17.7-.5 12 .8 19.3 4.9 27.5 8.3 16.8 26.8 26.2 51.1 26.2 20.1 0 34.3-5.2
45.6-16.6l6.2-6.4v20h56v-59c0-63.8-.3-67.8-5.5-79.6-8.6-19.3-29.8-32.2-56.2-33.9-4.3-.3-9.6-.7-11.8-.9-2.2-.1-8.5.1-14
.5m30.5 110.1c0 9.8-1.4 14.2-6.1 19.3-9.9 10.7-31.7 12.6-40.4
3.5-2.6-2.8-3-3.9-3.3-10.3-.7-15.4 6.5-19.5 34.6-19.6l15.2-.1zm194.6-110c-18.5
1.7-35.5 11.6-44.8 26l-3.3
5.3-.3-13.8-.3-13.7H2043v169h57.9l.3-53.3c.3-53.1.3-53.2 2.6-58.2 4.8-10.5
12.4-15.6 24.2-16.3 16.7-1 24.6 5.3 27 21.5.5 3.7 1 29.2 1
56.5V740h57v-60.3c0-55.1-.2-61-1.9-69.4-4.5-21.7-15.9-35.2-34.6-40.8-9.3-2.8-15.9-3.3-26.9-2.3m608.9
0c-18.8 1.6-36.8 12.2-45.3 26.8l-2.7
4.5-.3-13.8-.3-13.7H2652v169h58v-51.8c0-57.3.1-58.3 6.3-66.3 1.7-2.3 5.5-5.4
8.2-6.8 4.4-2.3 6.2-2.6 14.5-2.6 7.4 0 10.4.4 13.4 1.9 4.9 2.5 9.2 8.4 10.5
14.7.7 3.2 1.1 24.1 1.1
57.9v53h58.1l-.3-62.8c-.4-68.3-.4-67.2-6.4-81.1-9.4-21.2-29.2-31.3-56.9-28.9m149.3
0c-29 2.7-52.3 24-61 55.9-2 7.4-2.3 10.5-2.2 26.4.1 21.2 1.6 28.3 9.1 43.5 6.1
12.5 17.8 24.6 29.3 30.3 24.2 11.9 52.2 9.7 70.9-5.7 5.4-4.5 11.8-11.8 13.4-15.4
2.1-4.5 2.7-1.8 2.7 12 0 24.9-3 34.8-12.5 42-6.9 5.2-13.9 7.2-25.5
7.1-16.3-.1-25.5-5.4-29.7-17.2l-1.8-5.1h-28.2c-32.5 0-29.6-1.2-26.2 11 3.6 13.3
12.1 26.3 21.8 33.4 20.7 15.2 57.5 21.2 92.7 15.1 34.2-6 54.9-22.5 63.6-50.7
2.3-7.3 2.3-7.3 2.6-93.1l.3-85.7H2970v12c0 6.6-.3 12-.7 12-.5
0-1.9-1.9-3.3-4.1-3.6-6-13.7-15-20.2-18.1-10.3-5-23.4-7-38-5.6m42.7 43.5c6.9 2.2
14.5 9.9 17.5 17.8 2.8 7.5 3.9 24.8 2 33.4-1.7 7.7-6.6 16.6-11.1 20.2-7 5.5-12.5
7.3-22.4 7.4-7.9
0-9.7-.4-14.7-2.8-7-3.4-13-10-16-17.6-2-4.9-2.2-7.4-2.2-20.1s.2-15.2
2.2-20.1c6.9-17.3 24.8-24.6 44.7-18.2m-500.2 22.5c.3 55 .5 63.1 2 68.7 3 11 7.4
19 14.6 26.2 10.6 10.6 22.3 14.9 40.3 14.9 22.6 0 40.3-8.4 50.3-24l4-6.2.3
13.6.3 13.6h57.9V571h-57.9l-.3 54.2c-.4 61.4-.2 60.3-8.3 68.3-5.3 5.3-11.6
7.5-21.4 7.5-8.9 0-14.3-2.1-18.4-6.9-5.5-6.6-5.7-8.5-5.7-68.3V571h-58.1z"/><path
fill="var(--bandung)" fill-opacity=".4" d="M2419.2 617.7 2419 740l-29.2.3-29.3.2
29.3.3c22.7.1 29.4-.1 29.7-1.1.3-.6.4-55.9.3-122.7l-.3-121.5zm-748.5-110c15.5.2
41.1.2 57 0 15.8-.1 3.2-.2-28.2-.2-31.3 0-44.3.1-28.8.2M2449.5 626c0 30.5.1 43
.2 27.7.2-15.2.2-40.2 0-55.5-.1-15.2-.2-2.7-.2 27.8m112-6c0 27.2.1 38.4.2
24.7.2-13.6.2-35.8 0-49.5-.1-13.6-.2-2.4-.2 24.8m90
35.7v84.8h58.5l-29-.2-29-.3-.3-84.5-.2-84.5zm170.7 28-.2 56.3-29.2.3-29.3.2
29.3.3c22.7.1 29.4-.1 29.7-1.1.3-.6.4-26.2.3-56.7l-.3-55.5zm-721 8-.2
48.3-29.2.3-29.3.2 29.3.3c22.7.1 29.4-.1
29.7-1.1.3-.6.4-22.6.3-48.7l-.3-47.5zm-157.5-21 10.2.4.4 4.9.4
5-.1-5.2-.1-5.3-10.5-.1h-10.5zm617.8
57v12.8h58.5l-29-.2-28.9-.3-.4-12.5-.3-12.5zm-890.2 13c15.8.2 41.6.2 57.5 0
15.8-.1 2.8-.2-28.8-.2s-44.6.1-28.7.2m299 0c8.2.2 21.2.2 29 0 7.8-.1
1.2-.3-14.8-.3-15.9 0-22.3.2-14.2.3m200 0c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3m687 0c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3"/><path fill="var(--dotid)" fill-opacity=".1"
d="M3165.2 506.7c9.2.2 24.5.2 34 0 9.5-.1 2-.3-16.7-.3s-26.5.2-17.3.3m105.1
0c13.1.2 34.3.2 47 0 12.8-.1 2.1-.2-23.8-.2-25.8 0-36.3.1-23.2.2m-121.8 117.8c0
64.3.1 90.5.2 58.2.2-32.4.2-85 0-117-.1-32-.2-5.6-.2 58.8"/><path fill="var(--bandung)"
fill-opacity=".3" d="M1716.8 695.7c4.5.2 11.9.2 16.5 0
4.5-.1.8-.3-8.3-.3s-12.8.2-8.2.3"/><path fill="var(--bandung)" fill-opacity=".2"
d="M2057.3 569.7c8.1.2 21.3.2 29.5 0 8.1-.1 1.4-.3-14.8-.3s-22.9.2-14.7.3m407
0c8.1.2 21.3.2 29.5 0 8.1-.1 1.4-.3-14.8-.3s-22.9.2-14.7.3m112 0c8.1.2 21.3.2
29.5 0 8.1-.1 1.4-.3-14.8-.3s-22.9.2-14.7.3m90 0c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3m317 0c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3m-1267 30c4.2.2 11.2.2 15.5 0
4.2-.1.7-.3-7.8-.3s-12 .2-7.7.3"/><path fill="var(--dotid)" fill-opacity=".6"
d="m3182.2 507.7 33.8.3.3 116.5.2 116.5V507.5h-68zm88.1 0c13.1.2 34.3.2 47 0
12.8-.1 2.1-.2-23.8-.2-25.8 0-36.3.1-23.2.2"/><path fill="var(--dotid)"
fill-opacity=".5" d="M3246.5 624.5c0 64.3.1 90.5.2 58.2.2-32.4.2-85
0-117-.1-32-.2-5.6-.2 58.8"/><path fill="var(--dotid)" fill-opacity=".4" d="M3165.2
741.7c9.2.2 24.5.2 34 0 9.5-.1 2-.3-16.7-.3s-26.5.2-17.3.3m105.1 0c13 .2 34.4.2
47.5 0 13-.1 2.3-.2-23.8-.2s-36.8.1-23.7.2"/><path fill="var(--bandung)"
fill-opacity=".8" d="M1643.5 624c0 64.1.1 90.3.2 58.3.2-32.1.2-84.5
0-116.5-.1-32.1-.2-5.9-.2 58.2m570 61c0 30.5.1 43 .2 27.7.2-15.2.2-40.2
0-55.5-.1-15.2-.2-2.7-.2 27.8m-201 .5c0 30.2.1 42.8.2 27.8.2-14.9.2-39.7
0-55-.1-15.3-.2-3.1-.2 27.2m-295.7 11.2c4.5.2 11.9.2 16.5 0
4.5-.1.8-.3-8.3-.3s-12.8.2-8.2.3"/><path fill="var(--bandung)" fill-opacity=".7"
d="M2057.3 570.7c8.1.2 21.3.2 29.5 0 8.1-.1 1.4-.3-14.8-.3s-22.9.2-14.7.3m407
0c8.1.2 21.3.2 29.5 0 8.1-.1 1.4-.3-14.8-.3s-22.9.2-14.7.3m112 0c8.1.2 21.3.2
29.5 0 8.1-.1 1.4-.3-14.8-.3s-22.9.2-14.7.3m90 0c8.1.2 21.3.2 29.5 0 8.1-.1
1.4-.3-14.8-.3s-22.9.2-14.7.3m332.2 0 28.5.3.3 78.3.2 78.2v-157H2970zm-1289.6
29.6c.4.4 7.5.6 15.7.5l14.9-.3-15.7-.5c-8.6-.3-15.4-.2-14.9.3m446.6 90.7c0
27.2.1 38.4.2 24.7.2-13.6.2-35.8 0-49.5-.1-13.6-.2-2.4-.2 24.8"/><path
fill="var(--dotid)" d="M3149 624.5V741h67V508h-67zm98-.1V741h43.8c43.8 0 59.4-.8
75.2-3.7 50.6-9.2 81.8-41.8 88.9-92.7 1.6-11.1
1.4-31.7-.4-44.5-2.7-19.1-8.2-34-17.6-47.6-15-21.8-38.6-35.5-71.8-41.7-10.7-2-15.6-2.2-64.8-2.6l-53.3-.4zm103.5-63.9c13.6
3.6 23.9 11.5 29.5 22.8 5.2 10.3 6.5 18.8 6.5 41.2 0 22.5-.9 27.8-7 40-9.4
18.8-26.2 26.4-58.7 26.5h-7.8V557.7l15.8.6c9.5.3 18.1 1.2 21.7 2.2m-270.2
121.3c-6.9 2.5-11.8 6.4-15.8 12.7-8.5 13.2-6.8 28.8 4.3 40.1 6.8 6.9 12.1 8.9
23.2 8.8 11.5 0 16.7-2 23.8-9.1 6.7-6.8 9.6-13.2
9.6-21.3.1-12.9-6.7-24.1-18.1-29.6-7.4-3.7-19.3-4.4-27-1.6"/><path
fill="var(--dotid)" fill-opacity=".7" d="M3313.5 624.5c0 35.7.1 50.2.2
32.2.2-18.1.2-47.3 0-65-.1-17.7-.2-3-.2 32.8"/><path fill="var(--strips)" fill-opacity=".2"
d="M1932.2 787.7c157.6.2 415.9.2 574 0 158-.1 29-.2-286.7-.2s-445 .1-287.3.2m0
32c157.6.2 415.9.2 574 0 158-.1 29-.2-286.7-.2s-445 .1-287.3.2"/><path
fill="var(--strips)" fill-opacity=".4" d="M1638.5 790.3c-10.7 6-10.5 21.1.4 26.7l4
2h576.5c405.8 0 577.3-.3 579.3-1.1 3.8-1.4 9-7.3 9.8-11
1.5-6.9-3.1-15.3-10-17.9-1.8-.7-193.1-1-579.3-1h-576.7z"/></svg>
      </div>
    </a>
    <div class="nav-desktop" id="navbarNav">
      <ul class="nav-desktop-list">
        <li class="nav-desktop-item nav-desktop-dropdown">
          <button class="nav-desktop-link nav-dd-trigger <?= $isPintasanActive ? 'is-current' : '' ?>" aria-expanded="false">
            <i class="fa-solid fa-grip" aria-hidden="true"></i>
            Pintasan
            <i class="fa-solid fa-chevron-down nav-dd-chevron" aria-hidden="true"></i>
          </button>
          <div class="nav-dd-panel">
            <a class="nav-dd-item <?= isActive('/p/sejarah') ?>" href="p/sejarah"><i class="fa-solid fa-landmark"></i>Sejarah</a>
            <a class="nav-dd-item <?= isActive('/p/budaya') ?>" href="p/budaya"><i class="fa-solid fa-broom-ball"></i>Budaya</a>
            <a class="nav-dd-item <?= isActive('/p/kuliner') ?>" href="p/kuliner"><i class="fa-solid fa-bowl-rice"></i>Kuliner</a>
            <a class="nav-dd-item <?= isActive('/p/layanan') ?>" href="p/layanan"><i class="fa-solid fa-bus"></i>Layanan</a>
            <a class="nav-dd-item <?= isActive('/p/wisata') ?>" href="p/wisata"><i class="fa-solid fa-map-location-dot"></i>Wisata</a>
            <a class="nav-dd-item <?= isActive('/p/penginapan') ?>" href="p/penginapan"><i class="fa-solid fa-hotel"></i>Penginapan</a>
          </div>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/p/informasi-terkini') ?>"
          href="p/informasi-terkini">
            <i class="fa-solid fa-newspaper" aria-hidden="true"></i>Informasi Terkini
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/b') ?>" href="b">
            <i class="fa-solid fa-book" aria-hidden="true"></i>Blogs
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/map') ?>" href="map">
            <i class="fa-solid fa-suitcase-rolling" aria-hidden="true"></i>Trip
            Planner
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/gallery') ?>" href="gallery">
            <i class="fa-solid fa-images" aria-hidden="true"></i>Gallery
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/p/kritik-dan-saran') ?>" href="kritik-dan-saran">
            <i class="fa-solid fa-envelope" aria-hidden="true"></i>Kritik dan Saran
          </a>
        </li>
      </ul>
    </div>
    <div class="navbar-actions">
      <button class="dm-btn" id="dmToggle" aria-label="Toggle dark mode"
        onclick="toggleDark(this)">
        <div class="dm-icon">
          <svg class="dm-svg" viewBox="0 0 20 20" fill="none"
               stroke="currentColor" stroke-width="1.8">
            <circle class="sun-core" cx="10" cy="10" r="4"/>
            <line class="sun-ray" x1="10" y1="1"   x2="10" y2="3"/>
            <line class="sun-ray" x1="10" y1="17"  x2="10" y2="19"/>
            <line class="sun-ray" x1="1"  y1="10"  x2="3"  y2="10"/>
            <line class="sun-ray" x1="17" y1="10"  x2="19" y2="10"/>
            <line class="sun-ray" x1="3.2"  y1="3.2"  x2="4.6"  y2="4.6"/>
            <line class="sun-ray" x1="15.4" y1="15.4" x2="16.8" y2="16.8"/>
            <line class="sun-ray" x1="15.4" y1="4.6"  x2="16.8" y2="3.2"/>
            <line class="sun-ray" x1="3.2"  y1="16.8" x2="4.6"  y2="15.4"/>
          </svg>
          <svg class="dm-svg" viewBox="0 0 20 20" fill="none"
               stroke="currentColor" stroke-width="1.8">
            <path class="moon-path" stroke-linecap="round" stroke-linejoin="round"
                  d="M17 12.5A7 7 0 0 1 9 4.5a7 7 0 1 0 8 8z"/>
            <circle class="star star-1" cx="15" cy="4"  r="0.8" fill="currentColor" stroke="none"/>
            <circle class="star star-2" cx="17" cy="7"  r="0.5" fill="currentColor" stroke="none"/>
            <circle class="star star-3" cx="13" cy="2"  r="0.5" fill="currentColor" stroke="none"/>
          </svg>
        </div>
      </button>
      <button class="search-btn" id="btn-search" aria-label="Search">
        <span class="search-btn-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2.2" stroke-linecap="round">
            <circle class="s-circle" cx="10.5" cy="10.5" r="6.5"/>
            <line class="s-handle" x1="15.5" y1="15.5" x2="21" y2="21"/>
            <line class="s-cross"  x1="4"    y1="17"   x2="20" y2="4"/>
          </svg>
        </span>
      </button>

      <?php if (isset($_SESSION['user'])): ?>
      <!-- Desktop: User Avatar + Dropdown -->
      <div class="nav-user-wrap" id="userDropdownWrap">
        <button class="nav-user-btn" id="userDropdownTrigger" aria-label="Menu pengguna">
          <img
            src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>"
            alt="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
            class="nav-user-avatar"
            referrerpolicy="no-referrer">
        </button>
        <div class="nav-user-panel" id="userDropdownPanel">
          <a href="/pages/profile" style="text-decoration: none;">
          <div class="nav-user-info">
            <img
              src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>"
              alt="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
              class="nav-user-avatar-lg"
              referrerpolicy="no-referrer">
            <div class="nav-user-meta">
              <div class="nav-user-name"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
              <div class="nav-user-email"><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
            </div>
          </div>
          </a>
          <div class="nav-user-divider"></div>
          <a href="/api/auth/logout.php" class="nav-user-logout">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
          </a>
        </div>
      </div>
      <?php else: ?>
      <!-- Desktop: Login Button -->
      <a href="/api/auth/google.php" class="nav-login-btn">
        <i class="fab fa-google"></i>
        <span>Masuk</span>
      </a>
      <?php endif; ?>

      <button class="navbar-toggler" type="button" id="navbarToggler" aria-label="Toggle Menu">
        <span class="hamburger-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2.2" stroke-linecap="round">
            <line class="h-top" x1="3" y1="6"  x2="21" y2="6"/>
            <line class="h-mid" x1="3" y1="12" x2="21" y2="12"/>
            <line class="h-bot" x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </span>
      </button>
    </div>
  </div>
</nav>
<div class="menu-overlay" id="menuOverlay"></div>
<div class="navbar-collapse" id="navbarNav-mobile">
  <ul class="navbar-nav">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle mb-2 <?= $isPintasanActive ? 'is-current' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
        <i class="fa-solid fa-grip"></i> Pintasan
      </a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item <?= isActive('/p/sejarah') ?>" href="sejarah"><i class="fa-solid fa-landmark me-2"></i>Sejarah</a></li>
        <li><a class="dropdown-item <?= isActive('/p/budaya') ?>" href="budaya"><i class="fa-solid fa-broom-ball me-2"></i>Budaya</a></li>
        <li><a class="dropdown-item <?= isActive('/p/kuliner') ?>" href="kuliner"><i class="fa-solid fa-bowl-rice me-2"></i>Kuliner</a></li>
        <li><a class="dropdown-item <?= isActive('/p/layanan') ?>" href="layanan"><i class="fa-solid fa-bus me-2"></i>Layanan</a></li>
        <li><a class="dropdown-item <?= isActive('/p/wisata') ?>" href="wisata"><i class="fa-solid fa-map-location-dot me-2"></i>Wisata</a></li>
        <li><a class="dropdown-item <?= isActive('/p/penginapan') ?>" href="penginapan"><i class="fa-solid fa-hotel me-2"></i>Penginapan</a></li>
      </ul>
    </li>
    <li class="nav-item"><a class="nav-link <?= isActive('/p/informasi-terkini') ?>" href="informasi-terkini"><i class="fa-solid fa-newspaper"></i>Informasi Terkini</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/b') ?>" href="b"><i class="fa-solid fa-book"></i>Blogs</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/map') ?>"
    href="map"><i class="fa-solid
    fa-suitcase-rolling"></i>Trip Planner</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/gallery') ?>"
      href="gallery"><i class="fa-solid
      fa-images"></i>Gallery</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/p/kritik-dan-saran') ?>" href="kritik-dan-saran"><i class="fa-solid fa-envelope"></i>Kritik dan Saran</a></li>
    <div class="weather" id="w"><small>Cek cuaca...</small></div>
  </ul>

  <!-- Mobile: User Section -->
  <div class="mobile-user-section">
    <?php if (isset($_SESSION['user'])): ?>
    <a href="/profile" style="text-decoration: none;">
    <div class="mobile-user-info">
      <img
        src="<?= htmlspecialchars($_SESSION['user']['avatar']) ?>"
        alt="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
        class="nav-user-avatar-lg"
        referrerpolicy="no-referrer">
      <div class="nav-user-meta">
        <div class="nav-user-name"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
        <div class="nav-user-email"><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
      </div>
    </div>
    </a>
    <a href="/api/auth/logout.php" class="nav-user-logout">
      <i class="fa-solid fa-right-from-bracket"></i> Keluar
    </a>
    <?php else: ?>
    <a href="/api/auth/google.php" class="nav-login-btn nav-login-btn--full">
      <i class="fab fa-google"></i> Masuk dengan Google
    </a>
    <?php endif; ?>
  </div>
</div>
<script>
(function () {
  const wrap = document.getElementById('userDropdownWrap');
  const trigger = document.getElementById('userDropdownTrigger');
  if (!wrap || !trigger) return;

  trigger.addEventListener('click', function (e) {
    e.stopPropagation();
    wrap.classList.toggle('dd-open');
  });

  document.addEventListener('click', function () {
    wrap.classList.remove('dd-open');
  });
})();
</script>

<div id="live-search-wrapper" role="search" aria-label="Pencarian situs">
  <div class="ls-inner container">
    <div class="ls-bar">
      <i class="fas fa-search ls-icon-search" aria-hidden="true"></i>
      <input type="text" id="searchInput2"
             placeholder="Cari..."
             autocomplete="off"
             spellcheck="false"
             aria-label="Kolom pencarian"
             aria-autocomplete="list"
             aria-controls="live-search-dropdown">
      <button class="ls-close-btn" id="ls-close-btn" aria-label="Tutup pencarian">
        <i class="fas fa-times" aria-hidden="true"></i>
      </button>
    </div>
    <div id="live-search-dropdown" role="listbox" aria-label="Hasil pencarian"></div>
  </div>
</div>
<div class="chatbot offcanvas" id="chatbot">
    <div class="offcanvas-header">
      <div class="chatbot-header container">
        <span class="offcanvas-title fw-semibold fs-5"><i class="fas fa-solid fa-circle-user
        me-2"></i>Asisten</span>
        <button aria-label="Close chat" class="close-btn" data-bs-dismiss="offcanvas"><i class="fa-solid fa-xmark"></i></button>
      </div>
    </div>
    <div class="offcanvas-body p-3">
      <div class="chat-messages" id="chat-messages"></div>
      <div class="border-top bottom-area p-2">
        <div class="input-group">
          <input type="text" id="message-input" class="input-chat" placeholder="Tanyakan sesuatu...">
        </div>
        <div class="button-group gap-2 mt-2">
          <button class="btn btn-primary btn-sm" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
          <button class="btn btn-accent btn-sm" onclick="clearChat()"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>
  </div>
<button class="fab fab-chatbot" id="chatbotFabBtn" aria-label="Open Chat"><i class="fas fa-comment-dots"></i></button>
<button id="scrollTopBtn" class="fab scroll-top-btn" aria-label="Scroll to top">↑</button>
<div class="main-content">