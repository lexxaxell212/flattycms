<?php
// $page_title = htmlspecialchars($_POST["title"] ?? ($page_title ?? SITE_NAME));

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActive(string $path): string {
    global $currentPath;
    return str_starts_with($currentPath, $path) ? 'is-current' : '';
}
// Dropdown
$pintasanPaths = [
    '/pages/sejarah',
    '/pages/budaya', 
    '/pages/kuliner',
    '/pages/layanan',
    '/pages/wisata',
    '/pages/penginapan'
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
  <link rel="preload" as="font" href="<?= FONTS_URL ?>inter-v20-latin-regular.woff2" type="font/woff2" crossorigin>
  <link rel="preload" as="font" href="<?= FONTS_URL ?>inter-v20-latin-700.woff2" type="font/woff2" crossorigin>
  <link rel="preload" as="font" href="<?= FONTS_URL ?>inter-v20-latin-900.woff2" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= CSS_URL ?>bs533.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>assets.css">
  <?php
  $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);
  $heroImg = $isMobile ? 'wisata-mobile.webp' : 'wisata.webp'; ?>
  <link rel="preload" as="image" href="<?= IMG_URL . $heroImg ?>" type="image/webp" fetchpriority="high">
  <link rel="preload" as="style" href="<?= CSS_URL ?>component.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>component.css" media="print" onload="this.media='all'">
  <link rel="preload" as="style" href="<?= CSS_URL ?>style.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>style.css" media="print" onload="this.media='all'">
  <link rel="preload" as="style" href="<?= CSS_URL ?>fa651.all.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css" media="print" onload="this.media='all'">
  <noscript>
    <link rel="stylesheet" href="<?= CSS_URL ?>component.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>style.css">
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
  
  <?php if (!empty($_SESSION['flash'])): ?>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    Toast.fire({
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
  
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      background: '#e8f1fb',
      color: '#071a36',
      iconColor: '#1e40af',
    });
  </script>
  
</head>
<body>
<style>
.nav-login-btn {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 7px 14px;
  border-radius: 10px;
  background: rgba(37, 99, 176, 0.09);
  color: var(--blue-900);
  font-size: 13.5px;
  font-weight: 500;
  text-decoration: none;
  white-space: nowrap;
  transition: background 0.2s ease, color 0.2s ease, transform 0.15s ease;
}
.nav-login-btn:hover {
  background: rgba(37, 99, 176, 0.16);
  color: var(--blue-700);
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
  border-radius: 14px;
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
  color: var(--blue-900);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.nav-user-email {
  font-size: 11.5px;
  color: var(--blue-900);
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
    <a class="navbar-brand" aria-label="Halaman awal" href="<?= BASE_URL ?>">
      <div class="logo-navbar"></div>
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
            <a class="nav-dd-item <?= isActive('/pages/sejarah') ?>" href="<?= PAGES_URL ?>sejarah"><i class="fa-solid fa-landmark"></i>Sejarah</a>
            <a class="nav-dd-item <?= isActive('/pages/budaya') ?>" href="<?= PAGES_URL ?>budaya"><i class="fa-solid fa-broom-ball"></i>Budaya</a>
            <a class="nav-dd-item <?= isActive('/pages/kuliner') ?>" href="<?= PAGES_URL ?>kuliner"><i class="fa-solid fa-bowl-rice"></i>Kuliner</a>
            <a class="nav-dd-item <?= isActive('/pages/layanan') ?>" href="<?= PAGES_URL ?>layanan"><i class="fa-solid fa-bus"></i>Layanan</a>
            <a class="nav-dd-item <?= isActive('/pages/wisata') ?>" href="<?= PAGES_URL ?>wisata"><i class="fa-solid fa-map-location-dot"></i>Wisata</a>
            <a class="nav-dd-item <?= isActive('/pages/penginapan') ?>" href="<?= PAGES_URL ?>penginapan"><i class="fa-solid fa-hotel"></i>Penginapan</a>
          </div>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/pages/informasi-terkini') ?>" href="<?= PAGES_URL ?>informasi-terkini">
            <i class="fa-solid fa-newspaper" aria-hidden="true"></i>Informasi Terkini
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/blogs') ?>" href="<?= BLOGS_URL ?>">
            <i class="fa-solid fa-book" aria-hidden="true"></i>Blogs
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/pages/map') ?>" href="<?=
          PAGES_URL ?>map">
            <i class="fa-solid fa-suitcase-rolling" aria-hidden="true"></i>Trip
            Planner
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/pages/gallery') ?>" href="<?=
          PAGES_URL ?>gallery">
            <i class="fa-solid fa-images" aria-hidden="true"></i>Gallery
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link <?= isActive('/pages/kritik-dan-saran') ?>" href="<?= PAGES_URL ?>kritik-dan-saran">
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
        <li><a class="dropdown-item <?= isActive('/pages/sejarah') ?>" href="<?= PAGES_URL ?>sejarah"><i class="fa-solid fa-landmark me-2"></i>Sejarah</a></li>
        <li><a class="dropdown-item <?= isActive('/pages/budaya') ?>" href="<?= PAGES_URL ?>budaya"><i class="fa-solid fa-broom-ball me-2"></i>Budaya</a></li>
        <li><a class="dropdown-item <?= isActive('/pages/kuliner') ?>" href="<?= PAGES_URL ?>kuliner"><i class="fa-solid fa-bowl-rice me-2"></i>Kuliner</a></li>
        <li><a class="dropdown-item <?= isActive('/pages/layanan') ?>" href="<?= PAGES_URL ?>layanan"><i class="fa-solid fa-bus me-2"></i>Layanan</a></li>
        <li><a class="dropdown-item <?= isActive('/pages/wisata') ?>" href="<?= PAGES_URL ?>wisata"><i class="fa-solid fa-map-location-dot me-2"></i>Wisata</a></li>
        <li><a class="dropdown-item <?= isActive('/pages/penginapan') ?>" href="<?= PAGES_URL ?>penginapan"><i class="fa-solid fa-hotel me-2"></i>Penginapan</a></li>
      </ul>
    </li>
    <li class="nav-item"><a class="nav-link <?= isActive('/pages/informasi-terkini') ?>" href="<?= PAGES_URL ?>informasi-terkini"><i class="fa-solid fa-newspaper"></i>Informasi Terkini</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/blogs') ?>" href="<?= BLOGS_URL ?>"><i class="fa-solid fa-book"></i>Blogs</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/pages/map') ?>"
    href="<?= PAGES_URL ?>map"><i class="fa-solid
    fa-suitcase-rolling"></i>Trip Planner</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/pages/gallery') ?>"
      href="<?= PAGES_URL ?>gallery"><i class="fa-solid
      fa-images"></i>Gallery</a></li>
    <li class="nav-item"><a class="nav-link <?= isActive('/pages/kritik-dan-saran') ?>" href="<?= PAGES_URL ?>kritik-dan-saran"><i class="fa-solid fa-envelope"></i>Kritik dan Saran</a></li>
    <div class="weather" id="w"><small>Cek cuaca...</small></div>
  </ul>

  <!-- Mobile: User Section -->
  <div class="mobile-user-section">
    <?php if (isset($_SESSION['user'])): ?>
    <a href="/pages/profile" style="text-decoration: none;">
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
        <button aria-label="Close chat" class="close-btn text-white" data-bs-dismiss="offcanvas"><i class="fa-solid fa-xmark"></i></button>
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
<button class="fab-chatbot" id="chatbotFabBtn" aria-label="Open Chat"><i class="fas fa-comment-dots"></i></button>
<button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top">↑</button>
<div class="main-content">