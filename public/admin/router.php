<?php
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = preg_replace('#^admin/?#', '', $request);
error_log("ADMIN REQUEST: [" . $request . "]");

$routes = [
    ''               => ADMIN_VIEW_PATH . 'dashboard.php',
    'blog-manager.php'   => ADMIN_VIEW_PATH . 'blog-manager.php',
    'pages-builder.php'  => ADMIN_VIEW_PATH . 'pages/index.php',
    'modal-manager.php'           => ADMIN_VIEW_PATH . 'modal-manager.php',
    'setting.php'        => ADMIN_VIEW_PATH . 'setting.php',
    'database-manager.php'     => ADMIN_VIEW_PATH . 'database-manager.php',
    'newsletter.php'     => ADMIN_VIEW_PATH . 'newsletter.php',
];

$view_content = $routes[$request] ?? null;