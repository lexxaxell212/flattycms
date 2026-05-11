<?php
$lib_path = dirname(__DIR__) . '/lib/functions.php';
if (!file_exists($lib_path)) die('lib/functions.php missing: ' . $lib_path);
require_once $lib_path;
autoload_core();

require_once 'includes/header.php';

$pdo = $GLOBALS["pdo"] ?? null;

if (!$pdo) die('DB connection not available');

if (isset($_POST['action']) && $_POST['action'] === 'toggle_maintenance') {
    $current = $pdo->query("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'")->fetchColumn();
    $newVal = $current === '1' ? '0' : '1';
    
    $stmt = $pdo->prepare("
        INSERT INTO admin_setting (setting_key, setting_value) 
        VALUES ('maintenance_mode', :val)
        ON DUPLICATE KEY UPDATE setting_value = :val
    ");
    $stmt->execute([':val' => $newVal]);
    
    header('Location: /admin/setting');
    exit();
}

$maintenance = $pdo->query("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'")->fetchColumn();

?>

<form method="POST" action="/admin/setting">
    <input type="hidden" name="action" value="toggle_maintenance">
    <button type="submit" class="btn <?= $maintenance === '1' ? 'btn-danger' : 'btn-success' ?>">
        <?= $maintenance === '1' ? '🔴 Matiin Maintenance' : '🟢 Hidupkan Maintenance' ?>
    </button>
    <small>Status: <strong><?= $maintenance === '1' ? 'ON' : 'OFF' ?></strong></small>
</form>

<?php
require 'includes/footer.php'; ?>