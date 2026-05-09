<?php
// Hello Core //
$lib_path = dirname(__DIR__) . "/lib/functions.php";
if (!file_exists($lib_path)) die("lib/functions.php missing: " . $lib_path);
require_once $lib_path;
autoload_core(); // Core Here //

$page_title = htmlspecialchars($_POST["title"] ?? ($page_title ?? "Ayokebandung.id"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?></title>

  <meta name="description" content="Eksplorasi Wisata, Kuliner, dan Budaya Bandung Terlengkap.">
  <meta name="theme-color" content="#ffffff">
  <link rel="canonical" href="<?= BASE_URL ?>">
  <link rel="icon" href="<?= IMG_URL ?>favicon.ico" type="image/x-icon">
  
  <link rel="preload" as="font" href="<?= FONTS_URL ?>inter-v20-latin-regular.woff2" type="font/woff2" crossorigin>
  <link rel="preload" as="font" href="<?= FONTS_URL ?>inter-v20-latin-900.woff2" type="font/woff2" crossorigin>

  <link rel="stylesheet" href="<?= CSS_URL ?>bs533.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>indexes.css">
  
  <?php
  $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT']);
  $heroImg = $isMobile ? 'wisata-mobile.webp' : 'wisata.webp'; ?>
  <link rel="preload" as="image" href="<?= IMG_URL . $heroImg ?>" type="image/webp" fetchpriority="high">
  
  <link rel="preload" as="style" href="<?= CSS_URL ?>style.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>style.min.css" media="print" onload="this.media='all'">
  
  <link rel="preload" as="style" href="<?= CSS_URL ?>fa651.all.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css" media="print" onload="this.media='all'">
  
  <noscript>
    <link rel="stylesheet" href="<?= CSS_URL ?>style.min.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css">
  </noscript>

  <script>
    const CONFIG = {
      baseUrl: '<?= BASE_URL ?>',
    };
  </script>

  <script src="<?= JS_URL ?>bs533.bundle.min.js" defer></script>
  <script src="<?= JS_URL ?>swal2.all.min.js" defer></script>
  <script src="<?= JS_URL ?>chat.js" defer></script>
  <script src="<?= JS_URL ?>live-search.js" defer></script>
  <script src="<?= JS_URL ?>navbar.js" defer></script>
  <script src="<?= JS_URL ?>weather.js" defer></script>
  <script src="<?= JS_URL ?>newsletter-form.js" defer></script>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a class="navbar-brand" aria-label="Halaman awal" href="<?= BASE_URL ?>">
        <div class="logo-navbar"></div>
      </a>
      <div class="navbar-actions">
        <button class="search-btn" id="btn-search" aria-label="Search">
            <span class="search-btn-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" 
         stroke-width="2.2" stroke-linecap="round">
                <circle class="s-circle" cx="10.5" cy="10.5" r="6.5"/>
                <line class="s-handle" x1="15.5" y1="15.5" x2="21" y2="21"/>
                <line class="s-cross" x1="4" y1="17" x2="20" y2="4"/>
              </svg>
            </span>
        </button>
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
      <div class="menu-overlay" id="menuOverlay"></div>
      <div class="navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle mb-2" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-grip"></i> Pintasan
            </a>
            <ul class="dropdown-menu p-5 me-2">
              <li><a class="dropdown-item" href="<?= PAGES_URL ?>sejarah"><i class="fa-solid fa-landmark me-2"></i>Sejarah</a></li>
              <li><a class="dropdown-item" href="<?= PAGES_URL ?>budaya"><i class="fa-solid fa-broom-ball me-2"></i>Budaya</a></li>
              <li><a class="dropdown-item" href="<?= PAGES_URL ?>kuliner"><i class="fa-solid fa-bowl-rice me-2"></i>Kuliner</a></li>
              <li><a class="dropdown-item" href="<?= PAGES_URL ?>layanan"><i class="fa-solid fa-bus me-2"></i>Layanan</a></li>
              <li><a class="dropdown-item" href="<?= PAGES_URL ?>wisata"><i class="fa-solid fa-map-location-dot me-2"></i>Wisata</a></li>
              <li><a class="dropdown-item" href="<?= PAGES_URL ?>penginapan"><i class="fa-solid fa-hotel me-2"></i>Penginapan</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="<?= PAGES_URL ?>informasi-terkini"><i class="fa-solid fa-newspaper"></i>Informasi Terkini</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BLOGS_URL ?>"><i
          class="fa-solid fa-book"></i>Blogs</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= PAGES_URL ?>panduan-maps"><i class="fa-solid fa-location-dot"></i>Panduan Maps</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= PAGES_URL ?>kritik-dan-saran"><i class="fa-solid fa-envelope"></i>Kritik dan Saran</a></li>
          <div class="toggle">
            <div class="switch" onclick="toggleDark(this)"><span></span></div>
            <span>Mode Gelap</span>
          </div>
          <div class="weather" id="w"><small>Cek cuaca...</small></div>
        </ul>
      </div>
    </div>
  </nav>
  <div id="live-search-wrapper" role="search" aria-label="Pencarian situs">
  <div class="ls-inner container">
    <div class="ls-bar">
      <i class="fas fa-search ls-icon-search" aria-hidden="true"></i>
      <input type="text" id="searchInput2"
             placeholder="Mau cari apa di Bandung?"
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