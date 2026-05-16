<?php
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = preg_replace('#^admin/?#', '', $request);
error_log("ADMIN REQUEST: [" . $request . "]");

$routes = [
    ''                 => ADMIN_VIEW_PATH . 'dashboard.php',
    'blog-manager'     => ADMIN_VIEW_PATH . 'blog-manager.php',
    'pages'            => ADMIN_VIEW_PATH . 'pages/index.php',
    'modal-manager'    => ADMIN_VIEW_PATH . 'modal-manager.php',
    'setting'          => ADMIN_VIEW_PATH . 'setting.php',
    'newsletter'       => ADMIN_VIEW_PATH . 'newsletter.php',
    // 'maps' => ADMIN_VIEW_PATH . 'maps.php',
    'feedback' => ADMIN_VIEW_PATH . 'feedback.php',
    'login'           => ADMIN_PATH . 'login.php',
    'logout'           => ADMIN_PATH . 'logout.php',
];

$view_content = $routes[$request] ?? null;