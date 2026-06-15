<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
load_site_settings();

require_once ROOT_PATH . "maintenance.php";

require_once ADMIN_PATH . "router.php";

// auth check
$public_routes = ['login', 'logout'];
if (!in_array($request, $public_routes) && empty($_SESSION['admin_id'])) {
  header('Location: /admin/login');
  exit;
}

$action_handlers = [
  'blog-manager' => LIB_PATH . 'blog-actions.php',
  'newsletter' => LIB_PATH . 'newsletter-actions.php',
  'setting' => LIB_PATH . 'setting-actions.php',
  'cmpt-manager' => LIB_PATH . 'cmpt-actions.php',
  'pages-builder' => LIB_PATH . 'pages-builder-actions.php',
  // 'maps' => LIB_PATH . 'maps-actions.php',
  // 'newsletter'  => LIB_PATH . 'newsletter-actions.php',
];

if (isset($action_handlers[$request])) {
  require_once $action_handlers[$request];
}


$page_title = $page_title ?? "Admin - " . SITE_NAME;

$standalone_routes = ['login', 'logout'];

if (isset($view_content) && file_exists($view_content)) {
  if (in_array($request, $standalone_routes)) {
    require_once $view_content;
  } else {
    require_once ADMIN_VIEW_PATH . "includes/header.php";
    require_once $view_content;
    require_once ADMIN_VIEW_PATH . "includes/footer.php";
  }
} else {
  http_response_code(404);
  require_once PUBLIC_PATH . "errors/404.php";
}
?>