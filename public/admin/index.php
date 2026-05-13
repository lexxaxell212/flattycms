<?php
require_once __DIR__ . "/../../bootstrap.php";
autoload_core();

require_once ROOT_PATH . "maintenance.php";

// Auth guard admin
if (!is_logged_in() || !is_admin()) {
    redirect('/login');
}

require_once ADMIN_PATH . "router.php";

$page_title = $page_title ?? "Admin - " . SITE_NAME;

if (isset($view_content) && file_exists($view_content)) {
    require_once ADMIN_VIEW_PATH . "includes/header.php";
    require_once $view_content;
    require_once ADMIN_VIEW_PATH . "includes/footer.php";
} else {
    http_response_code(404);
    require_once PUBLIC_PATH . "errors/404.php";
}
?>