<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$routes = [
  // home
    '' => SRC_PATH . 'pages/home.php',
  // app
    'trip'       => SRC_PATH . 'apps/trip.php',
    'trips'       => SRC_PATH . 'apps/trip.php',
    'gallery'    => SRC_PATH . 'apps/gallery.php',
  // page
    'things-to-do'              => SRC_PATH . 'pages/things-to-do.php',
    'upcoming-events'           => SRC_PATH . 'pages/upcoming-events.php',
  // etc pages
    'pages/sejarah'              => SRC_PATH . 'pages/sejarah.php',
    'pages/tentang'              => SRC_PATH . 'pages/tentang.php',
    'pages/layanan'              => SRC_PATH . 'pages/layanan.php',
    'pages/privacy-policy'       => SRC_PATH . 'pages/privacy-policy.php',
    'pages/kritik-dan-saran'     => SRC_PATH . 'pages/kritik-dan-saran.php',
  // blogs
    'blogs'                      => SRC_PATH . 'blogs/index.php',
  // user
    'register'        => SRC_PATH . 'user/register.php',
    'login'           => SRC_PATH . 'user/login.php',
    'forgot-password' => SRC_PATH . 'user/forgot-password.php',
    'reset-password'  => SRC_PATH . 'user/reset-password.php',
    'profile'         => SRC_PATH . 'user/user-profile.php',
    'unsubscribe'     => SRC_PATH . 'user/unsubscribe.php',
];

// dynamic pages for pages builder
if (array_key_exists($uri, $routes)) {
    $view_content = $routes[$uri];
} elseif (str_starts_with($uri, 'pages/')) {
    $slug         = substr($uri, 6);
    $page_file    = PUBLIC_PATH . 'pages/' . $slug . '/index.php';
    $view_content = file_exists($page_file) ? $page_file : null;
} elseif (str_starts_with($uri, 'poi/')) {
    $poi_url      = substr($uri, 4);
    $view_content = SRC_PATH . 'pages/poi/index.php';
} else {
    $view_content = null;
}