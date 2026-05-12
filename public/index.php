<?php
require_once __DIR__ . "/../bootstrap.php";
autoload_core();

$page_title = "Home - Ayokebandung.id";

require_once SRC_PATH . "header.php";
?>

<style>
.main-content { padding-top: var(--navbar-height) !important; }
</style>

<script src="<?= JS_URL ?>hero.js" defer></script>
<script src="<?= JS_URL ?>autoslides.js" defer></script>
<script src="<?= JS_URL ?>card-slider.js" defer></script>

<?php

safe_include(SRC_PATH . "partials/hero.php", "Parts Hero Section");
safe_include(SRC_PATH . "partials/kenapa-bandung.php", "Parts Kenapa Bandung");
safe_include(SRC_PATH . "partials/blog-card.php", "Parts Artikel Terbaru");
safe_include(SRC_PATH . "partials/update-card.php", "Parts Update Terkini");
?>

<?php
require_once SRC_PATH . "footer.php";
?>