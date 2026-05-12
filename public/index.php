<?php
require_once __DIR__ . "/../bootstrap.php";
autoload_core();

require_once __DIR__ . "/router.php"; 

$page_title = $page_title ?? "Ayokebandung.id";

require_once SRC_PATH . "header.php";

echo '<div class="main-content">';

if (isset($view_content) && file_exists($view_content)) {
    require_once $view_content;
} else {
    http_response_code(404);
    require_once SRC_PATH . "errors/404.php";
}

echo '</div>';

require_once SRC_PATH . "footer.php"; 
?>
