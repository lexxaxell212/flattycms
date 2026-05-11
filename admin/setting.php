<?php
$lib_path = dirname(__DIR__) . '/lib/functions.php';
if (!file_exists($lib_path)) die('lib/functions.php missing: ' . $lib_path);
require_once $lib_path;
autoload_core();

require_once 'includes/header.php';

$pdo = $GLOBALS["pdo"] ?? null;
if (!$pdo) die('DB connection not available');

$success = null;

if (isset($_POST['action']) && $_POST['action'] === 'toggle_maintenance') {
    $current = $pdo->query("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'")->fetchColumn();
    $newVal = $current === '1' ? '0' : '1';

    $stmt = $pdo->prepare("
        INSERT INTO admin_setting (setting_key, setting_value)
        VALUES ('maintenance_mode', :val)
        ON DUPLICATE KEY UPDATE setting_value = :val
    ");
    $stmt->execute([':val' => $newVal]);

    header('Location: /admin/setting?saved=1');
    exit();
}

$maintenance = $pdo->query("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'")->fetchColumn();
$saved = isset($_GET['saved']);
?>

<!-- Toast -->
<?php if ($saved): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa-solid fa-circle-check me-2"></i> Setting berhasil disimpan.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        document.querySelectorAll('.toast').forEach(t => {
            bootstrap.Toast.getOrCreateInstance(t).hide();
        });
    }, 3000);
</script>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-semibold mb-0"><i class="fa-solid fa-sliders me-2 text-primary"></i>Settings</h4>
        <small class="text-muted">Kelola konfigurasi sistem</small>
    </div>
</div>

<!-- Setting Card: Site -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                <i class="fa-solid fa-globe fa-sm"></i>
            </span>
            <span class="fw-semibold">Site</span>
        </div>
    </div>
    <div class="card-body px-4 py-3">

        <!-- Maintenance Mode Row -->
        <div class="d-flex align-items-center justify-content-between py-3">
            <div>
                <div class="fw-medium mb-1">
                    <i class="fa-solid fa-triangle-exclamation me-1 <?= $maintenance === '1' ? 'text-warning' : 'text-muted' ?>"></i>
                    Maintenance Mode
                </div>
                <small class="text-muted">Saat aktif, pengunjung akan melihat halaman maintenance.</small>
            </div>
            <form method="POST" action="/admin/setting" class="ms-4 flex-shrink-0">
                <input type="hidden" name="action" value="toggle_maintenance">
                <div class="form-check form-switch m-0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="maintenanceToggle"
                        style="width:2.8em; height:1.5em; cursor:pointer;"
                        <?= $maintenance === '1' ? 'checked' : '' ?>
                        onchange="this.form.submit()"
                    >
                </div>
            </form>
        </div>

        <!-- Divider untuk setting berikutnya nanti -->
        <!-- <hr class="my-0"> -->

    </div>
</div>

<!-- Footer hint -->
<p class="text-muted small"><i class="fa-regular fa-clock me-1"></i> Perubahan langsung aktif tanpa restart.</p>

<?php require 'includes/footer.php'; ?>