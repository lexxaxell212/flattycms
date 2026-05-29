<?php
require_once __DIR__ . "/../bootstrap.php";
autoload_core();
load_site_settings();

require_once PUBLIC_PATH . "router.php";

$protected_routes = ['profile'];
if (in_array($uri, $protected_routes) && !isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

require_once ROOT_PATH . "maintenance.php";

$page_title = $page_title ?? SITE_NAME;

if (isset($view_content) && file_exists($view_content)) {
    require_once SRC_PATH . "headerv2.php";
    require_once LIB_PATH . 'analytics.php';
    record_pageview($pdo);
    require_once $view_content;
    require_once SRC_PATH . "footer.php";
} else {
    http_response_code(404);
    require_once PUBLIC_PATH . "errors/404.php";
}
?>