<?php
$admin_name = htmlspecialchars($_SESSION['admin_name'] ?? 'Admin');
$admin_url  = defined('ADMIN_URL') ? ADMIN_URL : '/admin/';
$current = basename($_SERVER['PHP_SELF'], '.php');
function nav_active(string $page, string $current): string {
    return $page === $current ? ' nav-active' : '';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="https://ayokebandung.id/assets/css/assets.css" rel="stylesheet">
  <link href="https://ayokebandung.id/assets/css/component.css" rel="stylesheet">
  <link href="https://ayokebandung.id/assets/css/style.css" rel="stylesheet">
  <style>
    #mobile-toggle {
      display: none;
    }

    .admin-container {
      display: flex;
      position: relative;
    }

    .mobile-header {
      display: flex;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: var(--blue-200);
      color: var(--blue-950);
      padding: 14px 20px;
      z-index: 3000;
      align-items: center;
      justify-content: space-between;
    }

    .mobile-menu-btn {
      background: none;
      border: none;
      font-size: 22px;
      color: var(--blue-950);
      cursor: pointer;
      padding: 6px 8px;
      border-radius: 8px;
      transition: color .2s;
    }

    .mobile-menu-btn:hover {
      color: var(--blue-800);
    }

    .mobile-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--blue-950);
    }

    .mobile-header-user {
      font-size: .8rem;
      color: var(--blue-800);
    }

    .sidebar {
      width: 260px;
      background: var(--blue-100);
      position: fixed;
      height: 100vh;
      top: 56px;
      left: 0;
      transform: translateX(-100%);
      transition: transform .3s cubic-bezier(.4, 0, .2, 1);
      z-index: 2000;
    }

    #mobile-toggle:checked~.admin-container .sidebar {
      transform: translateX(0);
    }

    #mobile-toggle:checked~.admin-container::before {
      content: "";
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .45);
      backdrop-filter: blur(3px);
      z-index: 1500;
    }

    .sidebar-header {
      padding: 20px;
      text-align: center;
      background: var(--blue-200);
      border-bottom: 1px solid var(--blue-300);
    }

    .logo {
      font-size: 20px;
      font-weight: 700;
      color: var(--blue-950);
      margin-bottom: 4px;
    }

    .sidebar-user {
      font-size: .78rem;
      color: var(--blue-800);
    }

    .sidebar-nav {
      padding: 12px 0;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 13px 20px;
      color: var(--blue-950);
      text-decoration: none;
      border-left: 3px solid transparent;
      font-size: .88rem;
      transition: all .2s;
    }

    .nav-item i {
      width: 18px;
      text-align: center;
      font-size: .9rem;
    }

    .nav-item:hover,
    .nav-item.nav-active {
      background: var(--blue-200);
      color: var(--blue-800);
      border-left-color: var(--blue-700);
    }

    .nav-logout {
      margin-top: 8px;
      border-top: 1px solid var(--blue-200);
    }

    .nav-logout:hover {
      color: #c0392b;
      border-left-color: #c0392b;
    }

    .main-content {
      flex: 1;
      margin-top: 56px;
      padding: 20px;
    }

    /* ── Topbar ── */
    .topbar {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      background: var(--blue-100);
      border: 1px solid var(--blue-200);
      border-radius: 12px;
      padding: 12px 20px;
      margin-bottom: 20px;
    }

    .topbar-user {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: .88rem;
      color: var(--blue-950);
    }

    .topbar-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--blue-300);
      color: var(--blue-950);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .9rem;
    }
  </style>
</head>

<body>
  <input type="checkbox" id="mobile-toggle">
  <header class="mobile-header">
    <label for="mobile-toggle" class="mobile-menu-btn" aria-label="Toggle menu">
      <i class="fas fa-bars"></i>
    </label>
    <span class="mobile-title">DASHBOARD</span>
    <span class="mobile-header-user">
      <i class="fas fa-user-circle" style="margin-right:5px"></i><?= $admin_name ?>
    </span>
  </header>

  <div class="admin-container">
    <aside class="sidebar" role="navigation" aria-label="Sidebar">
      <div class="sidebar-header">
        <div class="logo"><i class="fas fa-shield-halved" style="margin-right:6px"></i>Admin</div>
        <small class="sidebar-user"><?= $admin_name ?></small>
      </div>
      <nav class="sidebar-nav">
        <a href="<?= $admin_url ?>" class="nav-item<?= nav_active('dashboard', $current) ?>"><i class="fas fa-gauge-high"></i>Dashboard</a>
        <a href="<?= $admin_url ?>database_manager" class="nav-item<?= nav_active('database_manager', $current) ?>"><i class="fas fa-database"></i>DB Manager</a>
        <a href="<?= $admin_url ?>newsletter" class="nav-item<?= nav_active('newsletter', $current) ?>"><i class="fas fa-envelope"></i>Newsletter</a>
        <a href="<?= $admin_url ?>pages" class="nav-item<?= nav_active('pages', $current) ?>"><i class="fas fa-file-lines"></i>Pages Builder</a>
        <a href="<?= $admin_url ?>blog_manager" class="nav-item<?= nav_active('blog_manager', $current) ?>"><i class="fas fa-pen-to-square"></i>Blog Builder</a>
        <a href="<?= $admin_url ?>modal_manager" class="nav-item<?= nav_active('modal_manager', $current) ?>"><i class="fas fa-puzzle-piece"></i>CMPT Manager</a>
        <a href="<?= $admin_url ?>setting" class="nav-item<?= nav_active('setting', $current) ?>"><i class="fas fa-gear"></i>Settings</a>
        <a href="<?= $admin_url ?>logout" class="nav-item nav-logout"><i class="fas fa-right-from-bracket"></i>Logout</a>
      </nav>
    </aside>
  </div>

  <div class="main-content">
