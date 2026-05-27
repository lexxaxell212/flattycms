<?php
$lihat_semua = isset($_GET['semua']);
$limit       = $lihat_semua ? 999 : 5;

$total     = (int)$pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$feedbacks = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC LIMIT $limit")->fetchAll();
?>

<main id="content">
<div class="container">

<!-- Header -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                <i class="fa-solid fa-message fa-sm"></i>
            </span>
            <span class="fw-semibold">Feedback</span>
            <span class="badge bg-primary ms-1"><?= $total ?> total</span>
        </div>
    </div>
</div>

<!-- List -->
<?php if (empty($feedbacks)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center text-muted py-5">
        <i class="fa-solid fa-inbox fa-2x mb-3 opacity-50 d-block"></i>
        Belum ada feedback
    </div>
</div>
<?php else: ?>
    <?php foreach ($feedbacks as $f): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body px-4 py-3">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="fw-semibold"><?= safe_html($f['nama']) ?></span>
                    <?php if ($f['email']): ?>
                    <small class="text-muted ms-2"><?= safe_html($f['email']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge <?= ($f['rating'] ?? 0) >= 8 ? 'bg-success' : (($f['rating'] ?? 0) >= 6 ? 'bg-warning' : 'bg-danger') ?>">
                        ⭐ <?= (int)($f['rating'] ?? 0) ?>/10
                    </span>
                    <small class="text-muted"><?= fmt_date($f['created_at'] ?? '', 'd M Y H:i') ?></small>
                </div>
            </div>
            <div class="small text-muted"><?= safe_html($f['pesan'] ?? '-') ?></div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (!$lihat_semua && $total > 5): ?>
    <div class="text-center mt-3">
        <a href="?semua=1" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-eye me-1"></i> Lihat Semua (<?= $total ?>)
        </a>
    </div>
    <?php elseif ($lihat_semua): ?>
    <div class="text-center mt-3">
        <a href="?" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-eye-slash me-1"></i> Tampilkan Lebih Sedikit
        </a>
    </div>
    <?php endif; ?>
<?php endif; ?>

</div>
</main>