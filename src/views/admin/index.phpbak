<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /admin/index.php');
    exit;
}
if (isset($_SESSION['admin_id'])) {
    header('Location: /admin/dashboard.php');
    exit;
}
header('Location: /admin/login.php');
exit;