<?php
require_once LIB_PATH . "blogs.php";

$cat_id = (int) ($_GET["cat"]  ?? 0);
$posts = safe_get_random_posts($pdo, 6, $cat_id);
$categories = safe_get_categories($pdo);
?>
<script src="<?= JS_URL ?>artikel-slider.js" defer></script>
<div class="container">
  <section id="artikel-terbaru" aria-label="Artikel Terbaru">
    <div class="mb-4">
      <span class="text-eyebrow" data-bhs="b.eyebrow">BLOGS</span>
      <h2 data-bhs="b.title">Artikel</h2>
      <p data-bhs="b.excerpt">
        Cerita, tips, dan rekomendasi terbaik untuk perjalananmu.
      </p>
    </div>
    <div id="artikel-slider-wrapper">
      <div class="artikel-slider-viewport">
        <div class="artikel-slider-track" role="list">
          <?php if (empty($posts)): ?>
          <div class="text-center text-muted py-5 w-100" role="listitem">
            <i class="fas fa-newspaper fs-1 d-block mb-3 opacity-50 mx-auto" aria-hidden="true"></i>
            <p>
              Belum ada artikel.
            </p>
          </div>
          <?php else : ?>
          <?php foreach ($posts as $p): ?>
          <div class="artikel-slide-card card card-flatty" style="box-shadow:none;" role="listitem">
            <div class="card-body">
              <span class="badge badge-white top-0 end-0 position-absolute m-4">
              <?= htmlspecialchars(
                $p["cat_name"],
                ENT_QUOTES,
                "UTF-8",
              ) ?>
              </span>
              <img
              class="asl-img card-img"
              src="<?= BASE_UPLOAD_URL . htmlspecialchars($p['image'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              onerror="this.onerror=null;this.src='/uploads/default.jpg'"
              alt="<?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              loading="lazy"
              >
              <a href="/blogs/?slug=<?= htmlspecialchars($p['slug'] ?? '') ?>" class="h4">
                <?= htmlspecialchars(mb_strlen($p['title'] ?? '') > 60 ?
                  mb_substr($p['title'], 0, 60) . '…' : ($p['title'] ?? ''),
                  ENT_QUOTES, 'UTF-8') ?>
              </a>
              <p class="text-muted mt-2">
                <?= safe_excerpt($p['excerpt'] ?? ($p['content'] ?? ''), 170) ?>
              </p>
            </div>
            <div class="card-footer">
              <a href="/blogs/?slug=<?= htmlspecialchars($p['slug'] ?? '') ?>" class="btn
                btn-primary btn-asl-read">
                <span data-bhs="btn.read.more">Baca Selengkapnya</span>
                <i class="arrow-icon fas fa-angle-right ms-1" aria-hidden="true"></i>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="asl-controls-row">
        <div class="artikel-slider-dots" role="group" aria-label="Navigasi slide"></div>
        <button class="asl-nav-btn artikel-btn-prev" aria-label="Artikel sebelumnya" disabled>
          <i class="fas fa-angle-left" aria-hidden="true"></i>
        </button>
        <button class="asl-nav-btn artikel-btn-next" aria-label="Artikel selanjutnya">
          <i class="fas fa-angle-right" aria-hidden="true"></i>
        </button>
      </div>
    </div>
  </section>
</div>
</main>