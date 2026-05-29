<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$redirectout = $_SESSION['redirect_after_logout'] ?? '/';

session_unset();
session_destroy();

session_start([
    "cookie_httponly" => true,
    "cookie_secure"   => isset($_SERVER["HTTPS"]),
    "cookie_samesite" => "Lax",
]);
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Berhasil logout'];
session_write_close();

header('Location: ' . $redirectout);
exit;