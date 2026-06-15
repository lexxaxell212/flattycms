<?php
$pdo = $GLOBALS["pdo"] ?? null;
if ($pdo) {
  $stmt = $pdo->prepare("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'");
  $stmt->execute();
  $row = $stmt->fetch();
  define('MAINTENANCE_MODE', $row ? (bool)$row['setting_value'] : false);
} else {
  define('MAINTENANCE_MODE', false); // fallback
}
if (isset($_GET['key'])) {
  if ($_GET['key'] === BYPASS_KEY) {
    setcookie('maintenance_bypass', hash('sha256', BYPASS_KEY), time() + 86400, '/', '', true, true);
    header('Location: /');
    exit();
  } else {
    http_response_code(404);
    exit();
  }
}
$isBypass = isset($_COOKIE['maintenance_bypass']) &&
$_COOKIE['maintenance_bypass'] === hash('sha256', BYPASS_KEY);

$isAsset = (bool) preg_match('/\.(css|js|webp|jpg|jpeg|png|svg|woff2|ico|gif)$/i',
  $_SERVER['REQUEST_URI']);

$isAdmin = str_starts_with($_SERVER['REQUEST_URI'], '/admin');

if (MAINTENANCE_MODE && !$isBypass && !$isAsset && !$isAdmin) {
  http_response_code(503);
  header('Retry-After: 3600');
  include_once SRC_PATH . 'maintenance-page.php';
  exit();
}