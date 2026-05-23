<?php
$stmt = $GLOBALS['pdo']->query("SELECT id, title, slug, updated_at FROM pages ORDER BY updated_at DESC LIMIT 10");
$pages = $stmt->fetchAll();

$featured = array_shift($pages);

$page_title = 'Things to Do';
?>

<main id="content" class="container-fluid">
  <div class="container">

    <section id="Things-to-do">
      <h2 class="fw-bold mb-1">Things to Do</h2>
      <p class="text-muted mb-0">Temukan aktivitas dan informasi menarik</p>
    </section>

    <?php if ($featured): ?>
    <a href="/pages/<?= safe_html($featured['slug']) ?>/" class="text-decoration-none d-block mb-4">
      <div class="card border-0 shadow featured-card position-relative overflow-hidden" style="border-radius:1.25rem;">
        <div class="card-body p-4 p-md-5">
          <span class="badge text-white mb-3 px-3 py-2" style="background:#0d6efd;font-size:.75rem;border-radius:2rem;">
            <i class="fas fa-star me-1"></i> Terbaru
          </span>
          <h3 class="fw-bold text-dark mb-2" style="font-size:1.6rem;">
            <?= safe_html($featured['title']) ?>
          </h3>
          <small class="text-muted">
            <i class="far fa-clock me-1"></i>
            <?= date('d M Y', strtotime($featured['updated_at'])) ?>
          </small>
          <div class="mt-4">
            <span class="btn btn-primary btn-sm px-4">
              Lihat Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
            </span>
          </div>
        </div>
        <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center pe-4 opacity-25" style="font-size:8rem;line-height:1;">
          <i class="fas fa-compass text-primary"></i>
        </div>
      </div>
    </a>
    <?php endif; ?>

    <?php if (!empty($pages)): ?>
    <div class="row g-3">
      <?php foreach ($pages as $p): ?>
      <div class="col-md-6 col-lg-4">
        <a href="/pages/<?= safe_html($p['slug']) ?>/" class="text-decoration-none">
          <div class="card border-0 shadow-sm h-100 page-card" style="border-radius:.875rem;">
            <div class="card-body p-4">
              <h6 class="fw-semibold text-dark mb-2"><?= safe_html($p['title']) ?></h6>
              <small class="text-muted">
                <i class="far fa-clock me-1"></i>
                <?= date('d M Y', strtotime($p['updated_at'])) ?>
              </small>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-3">
              <small class="text-primary fw-medium">
                Baca <i class="fas fa-arrow-right ms-1" style="font-size:.7rem;"></i>
              </small>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!$featured && empty($pages)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-file fa-3x mb-3 opacity-50 d-block"></i>
      Belum ada halaman tersedia.
    </div>
    <?php endif; ?>

  </div>
</main>

<style>
.featured-card {
  background: linear-gradient(135deg, #f0f6ff 0%, #e8f0fe 100%);
  transition: transform .2s, box-shadow .2s;
}
.featured-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 1rem 2rem rgba(13,110,253,.15) !important;
}
.page-card {
  transition: transform .2s, box-shadow .2s;
}
.page-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important;
}
</style>