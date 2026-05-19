<?php
$admin_name = htmlspecialchars($_SESSION['admin_name'] ?? 'Admin');
$admin_url  = defined('ADMIN_URL') ? ADMIN_URL : '/admin/';
$request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$current = preg_replace('#^admin/?#', '', $request_path);

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?></title>

  <meta name="description" content="Admin Dashboard">
  <meta name="theme-color" content="#ffffff">
  <link rel="canonical" href="<?= BASE_URL ?>">
  <link rel="icon" href="<?= IMG_URL ?>favicon.ico" type="image/x-icon">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= CSS_URL ?>bs533.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>flattypurple.css">
  
  <link rel="preload" as="style" href="<?= CSS_URL ?>flattyui.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>flattyui.css" media="print" onload="this.media='all'">
  
  <link rel="preload" as="style" href="<?= CSS_URL ?>fa651.all.min.css">
  <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css" media="print" onload="this.media='all'">
  
  <noscript>
    <link rel="stylesheet" href="<?= CSS_URL ?>flattyui.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>fa651.all.min.css">
  </noscript>

  <script src="<?= JS_URL ?>bs533.bundle.min.js" defer></script>
  <script src="<?= JS_URL ?>swal2.all.min.js" defer></script>
  <script src="<?= JS_URL ?>navbar.js" defer></script>
  <script>
    const CONFIG = {
        baseUrl: '<?= BASE_URL ?>',
        csrfToken: '<?= generate_csrf_token() ?>',
    };
  </script>
</head>
<body>
<nav class="navbar">
  <div class="container">
    <a class="navbar-brand" aria-label="Admin Dashboard" href="<?= $admin_url
    ?>">
      flattyDash
    </a>
    <div class="nav-desktop" id="navbarNav">
      <ul class="nav-desktop-list">
        <li class="nav-desktop-item nav-desktop-dropdown">
          <button class="nav-desktop-link nav-dd-trigger" aria-expanded="false">
            <i class="fa-solid fa-grip" aria-hidden="true"></i>
            Profil
            <i class="fa-solid fa-chevron-down nav-dd-chevron" aria-hidden="true"></i>
          </button>
          <div class="nav-dd-panel">
            <a class="nav-dd-item" href="#"><i class="fa-solid fa-user-tie"></i>
                <strong><?= $admin_name?></strong>
            </a>
            <a class="nav-dd-item" href="<?= $admin_url
          ?>logout">
              <small>
                <i class="fa-solid fa-right-from-bracket"
                aria-hidden="true"></i>logout
                </small>
              </a>
          </div>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>blog-manager">
            <i class="fa-solid fa-newspaper" aria-hidden="true"></i>Kelola Blogs
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>pages">
            <i class="fa-solid fa-file" aria-hidden="true"></i>Buat Halaman
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>modal-manager">
            <i class="fa-solid fa-layer-group" aria-hidden="true"></i>CMPT
            Manager
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>newsletter">
            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>Newsletter
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>poi-manager">
            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>POI
            Manager
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>feedback">
            <i class="fa-solid fa-message" aria-hidden="true"></i>Feedback
          </a>
        </li>
        <li class="nav-desktop-item">
          <a class="nav-desktop-link" href="<?= $admin_url
          ?>setting">
            <i class="fa-solid fa-gear" aria-hidden="true"></i>Pengaturan
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
          <a class="nav-link dropdown-toggle mb-2" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fa-solid fa-grip"></i> Profil
          </a>
          <ul class="dropdown-menu">
            <li>
               <a class="dropdown-item" href="#"><i class="fa-solid fa-user-tie"></i>
                <strong><?= $admin_name?></strong>
            </a>
            <a class="dropdown-item" href="<?= $admin_url ?>logout">
              <small>
                <i class="fa-solid fa-right-from-bracket"
                aria-hidden="true"></i>logout
                </small>
              </a>
            </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>blog-manager">
            <i class="fa-solid fa-newspaper" aria-hidden="true"></i>Kelola Blogs
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>pages">
            <i class="fa-solid fa-file" aria-hidden="true"></i>
            Buat Halaman
            </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>modal-manager">
            <i class="fa-solid fa-layer-group" aria-hidden="true"></i>CMPT
            Manager
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>newsletter">
            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>Newsletter
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>feedback">
            <i class="fa-solid fa-message" aria-hidden="true"></i>Feedback
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>poi-manager">
            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>POI
            Manager
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $admin_url
          ?>setting">
            <i class="fa-solid fa-gear" aria-hidden="true"></i>Pengaturan
          </a>
        </li>
      </ul>
    </div>
<button id="scrollTopBtn" class="scroll-top-btn" aria-label="Scroll to top">↑</button>
<div class="main-content">