<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index');
    exit;
}
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard');
    exit;
}
header('Location: login');
exit;