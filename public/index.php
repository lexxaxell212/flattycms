<?php
////===///// LOAD CORE /////===////
require_once __DIR__ . "/../bootstrap.php";
////========/////////========////
autoload_core();
load_site_settings();
/////////
require_once LIB_PATH . 'analytics.php';
record_pageview($pdo);
////============>>>>
////=========>>>>
////======>>>>
///
// Maintenance check
require_once ROOT_PATH . "maintenance.php";
// Router
require_once PUBLIC_PATH . "router.php";
////// =============================== //////
$page_title = $page_title ?? SITE_NAME;
if (isset($view_content) && file_exists ($view_content)) {
    require_once SRC_PATH . "header.php";
    require_once $view_content;
    require_once SRC_PATH . "footer.php";
} else {
    http_response_code(404);
    require_once SRC_PATH . "header.php";
    require_once PUBLIC_PATH . "errors/404.php";
    require_once SRC_PATH . "footer.php";
}
////// =============================== //////
?>