<?php
require_once LIB_PATH . 'analytics.php';

try {
    $total_subs   = (int)$pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();
    $today_subs   = (int)$pdo->query("SELECT COUNT(*) FROM subscribers WHERE DATE(subscribed_at) = CURDATE()")->fetchColumn();
    $total_posts  = (int)$pdo->query("SELECT COUNT(*) FROM allcontent_posts WHERE status='active'")->fetchColumn();
    $total_pages  = (int)$pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
    $analytics    = get_analytics_summary($pdo);
    $top_pages    = get_top_pages($pdo);
    $daily_views  = get_daily_views($pdo, 7);
    $top_referrers = get_top_referrers($pdo);
} catch (Exception $e) {
    error_log('[dashboard] ' . $e->getMessage());
}

$csrf = generate_csrf_token();
?>

<div class="container py-5">

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-2 lh-1">
                        <i class="fa-solid fa-eye fa-sm"></i>
                    </span>
                    <small class="text-muted">Hari ini</small>
                </div>
                <div class="fs-3 fw-bold"><?= number_format($analytics['today'] ?? 0) ?></div>
                <div class="text-muted small">Views</div>
                <div class="text-muted small mt-1">
                    <i class="fa-solid fa-user me-1 text-primary"></i>
                    <?= number_format($analytics['unique_today'] ?? 0) ?> unique visitor
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-2 lh-1">
                        <i class="fa-solid fa-chart-line fa-sm"></i>
                    </span>
                    <small class="text-muted">7 Hari</small>
                </div>
                <div class="fs-3 fw-bold"><?= number_format($analytics['week'] ?? 0) ?></div>
                <div class="text-muted small">Views</div>
                <div class="text-muted small mt-1">
                    <i class="fa-solid fa-user me-1 text-primary"></i>
                    <?= number_format($analytics['unique_week'] ?? 0) ?> unique visitor
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="bg-success bg-opacity-10 text-success rounded p-2 lh-1">
                        <i class="fa-solid fa-envelope fa-sm"></i>
                    </span>
                    <small class="text-muted">Hari ini</small>
                </div>
                <div class="fs-3 fw-bold"><?= number_format($total_subs) ?></div>
                <div class="text-muted small">Subscriber</div>
                <div class="text-muted small mt-1">
                    <i class="fa-solid fa-arrow-up me-1 text-success"></i>
                    +<?= $today_subs ?> hari ini
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="bg-warning bg-opacity-10 text-warning rounded p-2 lh-1">
                        <i class="fa-solid fa-newspaper fa-sm"></i>
                    </span>
                    <small class="text-muted">Total</small>
                </div>
                <div class="fs-3 fw-bold"><?= number_format($total_posts) ?></div>
                <div class="text-muted small">Blog Posts</div>
                <div class="text-muted small mt-1">
                    <i class="fa-solid fa-file me-1 text-info"></i>
                    <?= number_format($total_pages) ?> pages
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart + Top Pages -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                        <i class="fa-solid fa-chart-area fa-sm"></i>
                    </span>
                    <span class="fw-semibold">Pengunjung 7 Hari</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="visitorChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                        <i class="fa-solid fa-ranking-star fa-sm"></i>
                    </span>
                    <span class="fw-semibold">Top Pages</span>
                </div>
            </div>
            <div class="card-body px-4 py-2">
                <?php foreach ($top_pages as $i => $tp): ?>
                <div class="d-flex align-items-center justify-content-between py-2 <?= $i < count($top_pages) - 1 ? 'border-bottom' : '' ?>">
                    <div class="text-truncate me-2" style="max-width:180px">
                        <small class="text-muted me-1">#<?= $i + 1 ?></small>
                        <span class="small"><?= safe_html($tp['page']) ?></span>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= number_format($tp['total']) ?></span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($top_pages)): ?>
                <div class="text-center text-muted py-4 small">Belum ada data</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Referrers + Quick Actions -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                        <i class="fa-solid fa-link fa-sm"></i>
                    </span>
                    <span class="fw-semibold">Top Referrers</span>
                </div>
            </div>
            <div class="card-body px-4 py-2">
                <?php foreach ($top_referrers as $i => $ref): ?>
                <div class="d-flex align-items-center justify-content-between py-2 <?= $i < count($top_referrers) - 1 ? 'border-bottom' : '' ?>">
                    <small class="text-truncate me-2" style="max-width:260px">
                        <?= safe_html(parse_url($ref['referrer'], PHP_URL_HOST) ?: $ref['referrer']) ?>
                    </small>
                    <span class="badge bg-success bg-opacity-10 text-success"><?= number_format($ref['total']) ?></span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($top_referrers)): ?>
                <div class="text-center text-muted py-4 small">Belum ada data</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
                        <i class="fa-solid fa-bolt fa-sm"></i>
                    </span>
                    <span class="fw-semibold">Quick Actions</span>
                </div>
            </div>
            <div class="card-body px-4 py-3">
                <div class="d-grid gap-2">
                    <a href="/admin/blog-manager" class="btn btn-outline-primary btn-sm text-start">
                        <i class="fa-solid fa-newspaper me-2"></i> Tambah Blog Post
                    </a>
                    <a href="/admin/pages" class="btn btn-outline-primary btn-sm text-start">
                        <i class="fa-solid fa-file me-2"></i> Buat Halaman Baru
                    </a>
                    <a href="/admin/modal-manager" class="btn btn-outline-primary btn-sm text-start">
                        <i class="fa-solid fa-layer-group me-2"></i> Kelola Modal/Card
                    </a>
                    <a href="/admin/newsletter" class="btn btn-outline-primary btn-sm text-start">
                        <i class="fa-solid fa-paper-plane me-2"></i> Kirim Newsletter
                    </a>
                    <a href="/admin/setting" class="btn btn-outline-primary btn-sm text-start">
                        <i class="fa-solid fa-gear me-2"></i> Pengaturan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const dailyData = <?= json_encode($daily_views ?? []) ?>;
    const labels = dailyData.map(d => d.date);
    const data   = dailyData.map(d => parseInt(d.total));

    new Chart(document.getElementById('visitorChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Pengunjung',
                data,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.08)',
                borderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>