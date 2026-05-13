<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

require_once ROOT_PATH . "maintenance.php";

// auth check
$public_routes = ['login', 'logout'];
if (!in_array($request, $public_routes) && empty($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit;
}

require_once ADMIN_PATH . "router.php";

$action_handlers = [
    'blog-manager' => LIB_PATH . 'blog-actions.php',
    // 'newsletter'  => LIB_PATH . 'newsletter-actions.php',
];

if (isset($action_handlers[$request])) {
    require_once $action_handlers[$request];
}


$page_title = $page_title ?? "Admin - " . SITE_NAME;

if (isset($view_content) && file_exists($view_content)) {
    require_once ADMIN_VIEW_PATH . "includes/header.php";
    require_once $view_content;
    require_once ADMIN_VIEW_PATH . "includes/footer.php";
} else {
    http_response_code(404);
    require_once ADMIN_VIEW_PATH . "includes/header.php";
    require_once PUBLIC_PATH . "errors/404.php";
    require_once ADMIN_VIEW_PATH . "includes/footer.php";
}
?>