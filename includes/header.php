<?php
$lib_path = dirname(__DIR__) . "/lib/functions.php";
if (!file_exists($lib_path)) die("lib/functions.php missing: " . $lib_path);
require_once $lib_path;
autoload_core();

// Handle title lebih clean
$page_title = htmlspecialchars($_POST["title"] ?? ($page_title ?? "Ayokebandung.id"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, is-mobile=1">
  <title><?= $page_title ?></title>
  
  <meta name="description" content="Eksplorasi Wisata, Kuliner, dan Budaya Bandung 2026 Terlengkap.">
  <meta name="theme-color" content="#ffffff">
  <link rel="canonical" href="<?= BASE_URL ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
  
  <link rel="stylesheet" href="<?= CSS_URL ?>glassmorphism-blue3.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>style.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>live-search.css">
  <link rel="icon" href="<?= IMG_URL ?>favicon.ico">
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a class="navbar-brand" href="<?= BASE_URL ?>">
        <div class="logo-navbar"></div>
      </a>
      <div class="navbar-actions">
        <button class="search-btn" id="btn-search" aria-label="Search">
          <i class="fas fa-magnifying-glass"></i>
        </button>
        <button class="navbar-toggler" type="button" id="navbarToggler" aria-label="Toggle Menu">
          <div class="hamburger"></div>
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
          <li class="nav-item"><a class="nav-link" href="<?= BLOG_URL ?>"><i class="fa-solid fa-book"></i>Blog</a></li>
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

  <div id="live-search-wrapper">
    <div class="search-input-bar container">
      <input type="text" id="searchInput2" placeholder="Mau cari apa di Bandung?">
      <div id="live-search-dropdown"></div>
    </div>
  </div>

  <div class="chatbot offcanvas" id="chatbot">
    <div class="offcanvas-header">
      <div class="chatbot-header container">
        <h6 class="offcanvas-title"><i class="fas fa-solid fa-circle-user me-2"></i>Asisten Web</h6>
        <button class="close-btn" data-bs-dismiss="offcanvas"><i class="fa-solid fa-xmark"></i></button>
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
