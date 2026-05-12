<?php
require_once __DIR__ . "/../bootstrap.php";
autoload_core();

require_once __DIR__ . "/router.php";
require_once LIB_PATH . "blogs.php";
require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";

$page_title = $page_title ?? "Ayokebandung.id";

require_once SRC_PATH . "header.php";
?>

<?php
if (isset($view_content) && file_exists($view_content)) {
    require_once $view_content;
} else {
    http_response_code(404);
    require_once SRC_PATH . "errors/404.php";
}
?>

<?php
require_once SRC_PATH . "footer.php"; 
?>
