<?php
require_once LIB_PATH . "blogs.php";

$cat_id   = (int) ($_GET["cat"]  ?? 0);
$page     = max(1, (int) ($_GET["page"] ?? 1));
$per_page = 10;
$offset   = ($page - 1) * $per_page;
$posts      = safe_get_posts($pdo, $per_page, $offset, $cat_id);
$categories = safe_get_categories($pdo);
?>
<script src="<?= JS_URL ?>artikel-slider.js" defer></script>
<section class="container" id="artikel-terbaru" aria-label="Artikel Terbaru">
    <span class="text-eyebrow">ARTIKEL</span>
    <h2>Blog Terbaru</h2>
    <p class="mb-4">
      Cerita, tips, dan rekomendasi terbaik untuk perjalananmu.
    </p>
    <div id="artikel-slider-wrapper">
      <div class="artikel-slider-viewport">
        <div class="artikel-slider-track" role="list">
            <?php if (empty($posts)): ?>
              <div class="text-center text-muted py-5 w-100">
                <i class="fas fa-newspaper fs-1 d-block mb-3 opacity-50" aria-hidden="true"></i>
                <p>Belum ada artikel.</p>
              </div>
            <?php else: ?>
              <?php foreach ($posts as $p): ?>
              <article class="artikel-slide-card card card-flatty" style="box-shadow:none;" role="listitem">
                <div class="card-body">
                  <img
                  class="asl-img card-img"
                  src="<?= BASE_UPLOAD_URL . htmlspecialchars($p['image'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  onerror="this.onerror=null;this.src='/uploads/default.jpg'"
                  alt="<?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  loading="lazy"
                >
                  <a href="/blogs/?id=<?= (int) $p['id'] ?>" class="h5 mb-2">
                  <?= htmlspecialchars(mb_strlen($p['title'] ?? '') > 60 ?
                  mb_substr($p['title'], 0, 60) . '…' : ($p['title'] ?? ''),
                  ENT_QUOTES, 'UTF-8') ?>
                  </a>
                  <p class="asl-excerpt small text-muted">
                    <?= safe_excerpt($p['excerpt'] ?? ($p['content'] ?? ''), 170) ?>
                  </p>
                </div>
                <div class="card-footer">
                  <a href="/blogs/?id=<?= (int) $p['id'] ?>" class="btn
                  btn-primary btn-sm btn-asl-read">
                    Baca Selengkapnya
                    <i class="arrow-icon fas fa-angle-right ms-1" aria-hidden="true"></i>
                  </a>
                </div>
              </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="asl-controls-row">
          <div class="artikel-slider-dots" role="tablist" aria-label="Navigasi slide"></div>
          <button class="asl-nav-btn artikel-btn-prev text-primary"
                  aria-label="Artikel sebelumnya" disabled>
            <i class="fas fa-angle-left" aria-hidden="true"></i>
          </button>
          <button class="asl-nav-btn artikel-btn-next text-primary"
                  aria-label="Artikel selanjutnya">
            <i class="fas fa-angle-right" aria-hidden="true"></i>
          </button>
      </div>
    </div>
</section>