<?php
$lib_path = dirname(__DIR__) . '/lib/functions.php';
if (!file_exists($lib_path)) die('lib/functions.php missing: ' . $lib_path);
require_once $lib_path;
autoload_core();

// 1. Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index');
    exit;
}

// 2. Sudah login → Dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard');
    exit;
}

// 3. Belum login → Login
header('Location: login');
exit;

?>
