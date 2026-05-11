<?php
define('BYPASS_KEY', 'lexxaccess');
define('MAINTENANCE_MODE', true); 

if (isset($_GET['key'])) {
    if ($_GET['key'] === BYPASS_KEY) {
        setcookie('maintenance_bypass', hash('sha256', BYPASS_KEY), time() + 86400, '/', '', true, true);
        header('Location: /');
        exit();
    } else {
        http_response_code(404);
        exit();
    }
}

$isBypass = isset($_COOKIE['maintenance_bypass']) && 
            $_COOKIE['maintenance_bypass'] === hash('sha256', BYPASS_KEY);

$isAsset  = (bool) preg_match('/\.(css|js|webp|jpg|jpeg|png|svg|woff2|ico|gif)$/i', 
            $_SERVER['REQUEST_URI']);

$isAdmin  = str_starts_with($_SERVER['REQUEST_URI'], '/admin');

if (MAINTENANCE_MODE && !$isBypass && !$isAsset && !$isAdmin) {
    http_response_code(503);
    header('Retry-After: 3600');
    include_once ROOT_PATH . 'maintenance-now.php';
    exit();
}