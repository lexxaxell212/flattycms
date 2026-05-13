<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$routes = [
  // home
    '' => SRC_PATH . 'pages/home.php',
  // pages
    'pages/wisata'              => SRC_PATH . 'pages/wisata.php',
    'pages/kuliner'              => SRC_PATH . 'pages/kuliner.php',
    'pages/penginapan'           => SRC_PATH . 'pages/penginapan.php',
    'pages/budaya'               => SRC_PATH . 'pages/budaya.php',
    'pages/sejarah'              => SRC_PATH . 'pages/sejarah.php',
    'pages/tentang'              => SRC_PATH . 'pages/tentang.php',
    'pages/layanan'              => SRC_PATH . 'pages/layanan.php',
    'pages/privacy-policy'       => SRC_PATH . 'pages/privacy-policy.php',
    'pages/unsubscribe'          => SRC_PATH . 'pages/unsubscribe.php',
    'pages/kritik-dan-saran'     => SRC_PATH . 'pages/kritik-dan-saran.php',
    'pages/panduan-maps'         => SRC_PATH . 'pages/panduan-maps.php',
    'pages/kenapa-harus-bandung' => SRC_PATH . 'pages/kenapa-harus-bandung.php',
    'pages/informasi-terkini'    => SRC_PATH . 'pages/informasi-terkini.php',
    'blogs'                      => SRC_PATH . 'blogs/index.php',
    //admin
    'admin/login'    => SRC_PATH . 'admin/login.php',
    'admin/dashboard'    => SRC_PATH . 'admin/dashboard.php',
    'admin/blog_manager'    => SRC_PATH . 'admin/blog_manager.php',
    'admin/pages_builder'    => SRC_PATH . 'admin/pages_builder.php',
    'admin/cmpt'    => SRC_PATH . 'admin/cmpt.php',
    'admin/setting'    => SRC_PATH . 'admin/setting.php',
    'admin/db_manager'    => SRC_PATH . 'admin/db_manager.php',
    'admin/newsletter'    => SRC_PATH . 'admin/newsletter.php',
];

if (array_key_exists($uri, $routes)) {
    $view_content = $routes[$uri];
} else {
    $view_content = null;
}
