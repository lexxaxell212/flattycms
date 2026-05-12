<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$routes = [
    ''                           => null,
    'wisata'               => ROOT_PATH . 'public/pages/wisata.php',
    'kuliner'              => ROOT_PATH . 'public/pages/kuliner.php',
    'penginapan'           => ROOT_PATH . 'public/pages/penginapan.php',
    'budaya'               => ROOT_PATH . 'public/pages/budaya.php',
    'sejarah'              => ROOT_PATH . 'public/pages/sejarah.php',
    'tentang'              => ROOT_PATH . 'public/pages/tentang.php',
    'layanan'              => ROOT_PATH . 'public/pages/layanan.php',
    'privacy-policy'       => ROOT_PATH . 'public/pages/privacy-policy.php',
    'unsubscribe'          => ROOT_PATH . 'public/pages/unsubscribe.php',
    'kritik-dan-saran'     => ROOT_PATH . 'public/pages/kritik-dan-saran.php',
    'panduan-maps'         => ROOT_PATH . 'public/pages/panduan-maps.php',
    'kenapa-harus-bandung' => ROOT_PATH . 'public/pages/kenapa-harus-bandung.php',
    'informasi-terkini'    => ROOT_PATH . 'public/pages/informasi-terkini.php',
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