<?php
require_once __DIR__ . "/../bootstrap.php";
autoload_core();

$page_title = "Home - Ayokebandung.id";

require_once SRC_PATH . "header.php";
require_once LIB_PATH . "blogs.php";
require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$routes = [
    ''                      => null, // home
    'pages/wisata'          => ROOT_PATH . 'public/pages/wisata.php',
    'pages/kuliner'         => ROOT_PATH . 'public/pages/kuliner.php',
    'pages/penginapan'      => ROOT_PATH . 'public/pages/penginapan.php',
    'pages/budaya'          => ROOT_PATH . 'public/pages/budaya.php',
    'pages/sejarah'         => ROOT_PATH . 'public/pages/sejarah.php',
    'pages/tentang'         => ROOT_PATH . 'public/pages/tentang.php',
    'pages/layanan'         => ROOT_PATH . 'public/pages/layanan.php',
    'pages/privacy-policy'  => ROOT_PATH . 'public/pages/privacy-policy.php',
    'pages/unsubscribe'     => ROOT_PATH . 'public/pages/unsubscribe.php',
    'pages/kritik-dan-saran'=> ROOT_PATH . 'public/pages/kritik-dan-saran.php',
    'pages/panduan-maps'    => ROOT_PATH . 'public/pages/panduan-maps.php',
    'pages/kenapa-harus-bandung' => ROOT_PATH . 'public/pages/kenapa-harus-bandung.php',
    'pages/informasi-terkini'    => ROOT_PATH . 'public/pages/informasi-terkini.php',
    'blogs'                 => ROOT_PATH . 'public/blogs/index.php',
];

if (array_key_exists($uri, $routes) && $routes[$uri] !== null) {
    require_once $routes[$uri];
    exit;
}
?>

<style>
.main-content { padding-top: var(--navbar-height) !important; }
</style>

<script src="<?= JS_URL ?>hero.js" defer></script>
<script src="<?= JS_URL ?>autoslides.js" defer></script>
<script src="<?= JS_URL ?>card-slider.js" defer></script>

<?php

safe_include(SRC_PATH . "partials/part-hero.php", "Parts Hero Section");
safe_include(SRC_PATH . "partials/part-kenapa.php", "Parts Kenapa Bandung");
safe_include(SRC_PATH . "partials/part-blogs.php", "Parts Artikel Terbaru");
safe_include(SRC_PATH . "partials/part-informasi.php", "Parts Update Terkini");
?>

<?php
require_once SRC_PATH . "footer.php";
?>