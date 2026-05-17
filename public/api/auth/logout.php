<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();
$redirect = $_SESSION['redirect_after_logout'] ?? '/';
unset($_SESSION['redirect_after_logout']);
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Berhasil logout'];
session_destroy();
header('Location: ' . $redirect);
exit;