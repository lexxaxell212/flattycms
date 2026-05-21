<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

$redirectout = $_SESSION['redirect_after_logout'] ?? '/';

session_unset();
session_destroy();

session_start();
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Berhasil logout'];
session_write_close();

header('Location: ' . $redirectout);
exit;