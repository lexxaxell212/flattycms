<?php
// CSRF token 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pdo = $GLOBALS['pdo'] ?? null;

try {
    if ($pdo === null) {
        throw new RuntimeException('Koneksi database tidak tersedia.');
    }

    $total_subs = (int) $pdo->query(
        "SELECT COUNT(*) FROM subscribers"
    )->fetchColumn();

    $today_subs = (int) $pdo->query(
        "SELECT COUNT(*) FROM subscribers WHERE DATE(subscribed_at) = CURDATE()"
    )->fetchColumn();

} catch (RuntimeException | PDOException $e) {
    $total_subs = 0;
    $today_subs = 0;
    $db_error   = 'Database tidak dapat dijangkau. Silakan coba lagi.';
    error_log('[dashboard] Error: ' . $e->getMessage());
}
?>

<div class="container py-5">
    <?php if (!empty($db_error)): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fa fa-triangle-exclamation"></i>
            <?= htmlspecialchars($db_error) ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-3 col-sm-2">
            <div class="card card-glass">
              <div class="card-body">
                <div class="mb-3"><?= number_format($total_subs) ?></div>
                <div class="mb-3">Total Subscriber</div>
                <div class="small">
                    <i class="fas fa-arrow-up"></i> +<?= $today_subs ?> hari ini
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-2">
            <div class="card card-glass">
              <div class="card-body">
                <div class="mb-3">—</div>
                <div class="mb-3">Total Pengunjung</div>
                <div class="small">
                    <i class="fas fa-clock"></i> Segera tersedia
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
