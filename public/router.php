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
    'pages/kenapa-harus-bandung' => SRC_PATH . 'pages/kenapa-harus-bandung.php',
    'pages/informasi-terkini'    => SRC_PATH . 'pages/informasi-terkini.php',
    'blogs'                      => SRC_PATH . 'blogs/index.php',
    'profile'    => SRC_PATH . 'pages/user-profile.php',
    'logout'  => PUBLIC_PATH . 'api/auth/logout.php',
    'map'        => SRC_PATH . 'pages/map.php',
    'gallery'    => SRC_PATH . 'pages/gallery.php',
    
];

if (array_key_exists($uri, $routes)) {
    $view_content = $routes[$uri];
} elseif (str_starts_with($uri, 'pages/')) {
    $slug         = substr($uri, 6);
    $page_file    = PUBLIC_PATH . 'pages/' . $slug . '/index.php';
    $view_content = file_exists($page_file) ? $page_file : null;
} else {
    $view_content = null;
}