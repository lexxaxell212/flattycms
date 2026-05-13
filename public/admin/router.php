<?php
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = preg_replace('#^admin/?#', '', $request);

$routes = [
    ''               => ADMIN_VIEW_PATH . 'dashboard.php',
    'blog-manager'   => ADMIN_VIEW_PATH . 'blog-manager.php',
    'pages-builder'  => ADMIN_VIEW_PATH . 'pages-builder.php',
    'cmpt'           => ADMIN_VIEW_PATH . 'cmpt.php',
    'setting'        => ADMIN_VIEW_PATH . 'setting.php',
    'db-manager'     => ADMIN_VIEW_PATH . 'db-manager.php',
    'newsletter'     => ADMIN_VIEW_PATH . 'newsletter.php',
];

$view_content = $routes[$request] ?? null;