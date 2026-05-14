<?php
$kategori_filter = $_GET['kategori'] ?? '';
$page            = max(1, (int)($_GET['page'] ?? 1));
$per_page        = 5;
$offset          = ($page - 1) * $per_page;

$kategori_list = ['desain', 'konten', 'fungsional', 'performance', 'seo', 'mobile', 'lainnya'];

$where  = $kategori_filter ? "WHERE kategori = " . $pdo->quote($kategori_filter) : '';
$total  = (int)$pdo->query("SELECT COUNT(*) FROM feedback $where")->fetchColumn();
$pages  = ceil($total / $per_page);

$feedbacks = $pdo->query("
    SELECT * FROM feedback $where 
    ORDER BY created_at DESC 
    LIMIT $per_page OFFSET $offset
")->fetchAll();
?>

<!-- Filter Kategori -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                <i class="fa-solid fa-comment fa-sm"></i>
            </span>
            <span class="fw-semibold">Feedback</span>
            <span class="badge bg-primary ms-1"><?= $total ?> total</span>
            <div class="ms-auto d-flex gap-2 flex-wrap">
                <a href="?kategori=" 
                   class="btn btn-sm <?= !$kategori_filter ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    Semua
                </a>
                <?php foreach ($kategori_list as $k): ?>
                <a href="?kategori=<?= $k ?>" 
                   class="btn btn-sm <?= $kategori_filter === $k ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <?= ucfirst($k) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- List Feedback -->
<?php if (empty($feedbacks)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center text-muted py-5">
        <i class="fa-solid fa-inbox fa-2x mb-3 opacity-50"></i>
        <div>Belum ada feedback</div>
    </div>
</div>
<?php else: ?>
    <?php foreach ($feedbacks as $f): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body px-4 py-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold"><?= safe_html($f['nama']) ?></span>
                    <?php if ($f['email']): ?>
                    <small class="text-muted"><?= safe_html($f['email']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border"><?= safe_html($f['kategori']) ?></span>
                    <span class="badge <?= $f['rating'] >= 8 ? 'bg-success' : ($f['rating'] >= 6 ? 'bg-warning' : 'bg-danger') ?>">
                        ⭐ <?= (int)$f['rating'] ?>/10
                    </span>
                    <small class="text-muted"><?= fmt_date($f['created_at'], 'd M Y H:i') ?></small>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small fw-medium text-danger mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i>Kritik</div>
                    <div class="small text-muted"><?= safe_html($f['pesan']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?kategori=<?= $kategori_filter ?>&page=<?= $page - 1 ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </li>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?kategori=<?= $kategori_filter ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?kategori=<?= $kategori_filter ?>&page=<?= $page + 1 ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
<?php endif; ?>