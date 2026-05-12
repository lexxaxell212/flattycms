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
echo "<pre>";

// Cek constants
echo "=== PATHS ===\n";
echo "ROOT_PATH: " . ROOT_PATH . "\n";
echo "LIB_PATH: " . LIB_PATH . "\n";
echo "SRC_PATH: " . SRC_PATH . "\n";
echo "CONFIG_PATH: " . CONFIG_PATH . "\n";
echo "LOGS_PATH: " . LOGS_PATH . "\n";
echo "BASE_UPLOAD_PATH: " . BASE_UPLOAD_PATH . "\n";

echo "\n=== URLS ===\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "ASSETS_URL: " . ASSETS_URL . "\n";
echo "BASE_UPLOAD_URL: " . BASE_UPLOAD_URL . "\n";

echo "\n=== ENV ===\n";
echo "APP_ENV: " . APP_ENV . "\n";
echo "DEBUG_MODE: " . (DEBUG_MODE ? 'true' : 'false') . "\n";

echo "\n=== DB ===\n";
$pdo = $GLOBALS['pdo'] ?? null;
if ($pdo) {
    echo "PDO: Connected ✅\n";
    try {
        $stmt = $pdo->query("SELECT 1");
        echo "Query test: OK ✅\n";
    } catch (Exception $e) {
        echo "Query error: " . $e->getMessage() . "\n";
    }
} else {
    echo "PDO: NOT connected ❌\n";
}

echo "\n=== FILES ===\n";
$files = [
    SRC_PATH . "header.php",
    SRC_PATH . "footer.php",
    SRC_PATH . "partials/hero.php",
    SRC_PATH . "partials/blog-card.php",
    SRC_PATH . "partials/kenapa-bandung.php",
    SRC_PATH . "partials/update-card.php",
    LIB_PATH . "helper.php",
    LIB_PATH . "blogs.php",
];

foreach ($files as $file) {
    echo ($file) . ": " . (file_exists($file) ? "EXISTS ✅" : "MISSING ❌") . "\n";
}

echo "</pre>";

require_once SRC_PATH . "footer.php";
?>