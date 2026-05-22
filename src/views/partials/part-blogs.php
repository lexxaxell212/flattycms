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

<style>
#artikel-slider-wrapper {
  --asl-gap:    1rem;
  --asl-radius: 1rem;
}

.artikel-slider-viewport {
  overflow-x: scroll;
  overflow-y: visible;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;          /* Firefox */
  -ms-overflow-style: none;       /* IE/Edge */
  padding-bottom: 4px;            /* room for hover shadow */
}
.artikel-slider-viewport::-webkit-scrollbar { display: none; }

.artikel-slider-track {
  display: flex;
  gap: var(--asl-gap);
}

.artikel-slide-card {
  /* desktop: 4 cards — subtract 3 gaps split across 4 items */
  flex: 0 0 calc((100% - var(--asl-gap) * 3) / 4);
  min-width: 0;
  scroll-snap-align: start;
  border-radius: var(--asl-radius);
  overflow: hidden;
  background: var(--bs-body-bg, #fff);
  border: 1px solid rgba(0,0,0,.09);
  transition: box-shadow .2s ease, transform .2s ease;
}
.artikel-slide-card:hover {
  box-shadow: 0 8px 28px rgba(0,0,0,.10);
  transform: translateY(-3px);
}

/* tablet: 2 cards */
@media (max-width: 1199px) {
  .artikel-slide-card {
    flex: 0 0 calc((100% - var(--asl-gap)) / 2);
  }
}

/* mobile: 1 card full width */
@media (max-width: 767px) {
  .artikel-slide-card {
    flex: 0 0 100%;
  }
}

.artikel-slide-card .asl-img {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
  display: block;
}

.artikel-slide-card .card-body {
  padding: 1rem 1.1rem 1.25rem;
}
.artikel-slide-card .asl-title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--bs-body-color);
  text-decoration: none;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: .5rem;
  line-height: 1.4;
}
.artikel-slide-card .asl-title:hover {
  text-decoration: underline;
  text-underline-offset: 3px;
}
.artikel-slide-card .asl-excerpt {
  font-size: .875rem;
  color: var(--bs-secondary-color, #6c757d);
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: .85rem;
  line-height: 1.55;
}
.artikel-slide-card .btn-asl-read {
  font-size: .8rem;
  font-weight: 600;
  letter-spacing: .03em;
  text-transform: uppercase;
  padding: .45rem 1rem;
  border-radius: 2rem;
}

.asl-controls-row {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: .5rem;
  margin-top: 1rem;
}

.artikel-slider-dots {
  display: flex;
  align-items: center;
  gap: .45rem;
  flex: 1;
}
.artikel-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: rgba(0,0,0,.18);
  border: none;
  padding: 0;
  cursor: pointer;
  transition: background .25s, width .25s;
}
.artikel-dot.is-active {
  background: var(--bs-primary, #0d6efd);
  width: 22px;
  border-radius: 4px;
}
@media (prefers-color-scheme: dark) {
  .artikel-dot            { background: rgba(255,255,255,.25); }
  .artikel-dot.is-active  { background: var(--bs-primary, #0d6efd); }
}

.asl-nav-btn {
  width: 40px;
  height: 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  border: 1.5px solid currentColor;
  background: transparent;
  cursor: pointer;
  transition: background .18s, color .18s, transform .15s;
  flex-shrink: 0;
  padding: 0;
  line-height: 1;
}
.asl-nav-btn:not(:disabled):hover {
  background: var(--bs-primary, #0d6efd);
  border-color: var(--bs-primary, #0d6efd);
  color: #fff;
  transform: scale(1.06);
}
.asl-nav-btn:disabled {
  opacity: .3;
  cursor: default;
}
</style>

<div class="container-fluid">
  <div class="container">
    <section id="artikel-terbaru" aria-label="Artikel Terbaru">

      <h2 class="mb-4">Artikel Terbaru</h2>

      <div id="artikel-slider-wrapper">

        <!-- Scrollable viewport -->
        <div class="artikel-slider-viewport">
          <div class="artikel-slider-track" role="list">
            <?php if (empty($posts)): ?>
              <div class="text-center text-muted py-5 w-100">
                <i class="fas fa-newspaper fs-1 d-block mb-3 opacity-50" aria-hidden="true"></i>
                <p>Belum ada artikel.</p>
              </div>
            <?php else: ?>
              <?php foreach ($posts as $p): ?>
              <article class="artikel-slide-card" role="listitem">
                <img
                  class="asl-img"
                  src="<?= BASE_UPLOAD_URL . htmlspecialchars($p['image'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  onerror="this.onerror=null;this.src='/uploads/default.jpg'"
                  alt="<?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  loading="lazy"
                >
                <div class="card-body">
                  <a href="/blogs/?id=<?= (int) $p['id'] ?>" class="asl-title">
                    <?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                  </a>
                  <p class="asl-excerpt">
                    <?= safe_excerpt($p['excerpt'] ?? ($p['content'] ?? ''), 130) ?>
                  </p>
                  <a href="/blogs/?id=<?= (int) $p['id'] ?>" class="btn btn-primary btn-asl-read">
                    Baca Selengkapnya
                    <i class="fas fa-angle-right ms-1" aria-hidden="true"></i>
                  </a>
                </div>
              </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div><!-- /.artikel-slider-viewport -->

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

      </div><!-- /#artikel-slider-wrapper -->

    </section>
  </div>
</div>