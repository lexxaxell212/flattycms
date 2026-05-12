<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$routes = [
    ''                           => null,
    'pages/wisata'               => ROOT_PATH . 'public/pages/wisata.php',
    'pages/kuliner'              => ROOT_PATH . 'public/pages/kuliner.php',
    'pages/penginapan'           => ROOT_PATH . 'public/pages/penginapan.php',
    'pages/budaya'               => ROOT_PATH . 'public/pages/budaya.php',
    'pages/sejarah'              => ROOT_PATH . 'public/pages/sejarah.php',
    'pages/tentang'              => ROOT_PATH . 'public/pages/tentang.php',
    'pages/layanan'              => ROOT_PATH . 'public/pages/layanan.php',
    'pages/privacy-policy'       => ROOT_PATH . 'public/pages/privacy-policy.php',
    'pages/unsubscribe'          => ROOT_PATH . 'public/pages/unsubscribe.php',
    'pages/kritik-dan-saran'     => ROOT_PATH . 'public/pages/kritik-dan-saran.php',
    'pages/panduan-maps'         => ROOT_PATH . 'public/pages/panduan-maps.php',
    'pages/kenapa-harus-bandung' => ROOT_PATH . 'public/pages/kenapa-harus-bandung.php',
    'pages/informasi-terkini'    => ROOT_PATH . 'public/pages/informasi-terkini.php',
    'blogs'                      => ROOT_PATH . 'public/blogs/index.php',
];

// exact match
if (array_key_exists($uri, $routes)) {
    if ($routes[$uri] !== null) {
        require_once $routes[$uri];
        exit;
    }
    // home
} else {
    // 404
    http_response_code(404);
    require_once ROOT_PATH . 'public/errors/404.php';
    exit;
}