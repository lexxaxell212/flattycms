<?php
$_tdo_stmt = $GLOBALS['pdo']->query("SELECT id, title, slug, updated_at FROM pages ORDER BY updated_at DESC LIMIT 3");
$_tdo_pages = $_tdo_stmt->fetchAll();
$_tdo_featured = array_shift($_tdo_pages);
?>

<?php if ($_tdo_featured): ?>
<section class="container-fluid py-5 tdo-section">
  <div class="container">

    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <span class="tdo-label">Things to Do</span>
        <h2 class="tdo-heading mb-0">Event & Info Terkini</h2>
      </div>
      <a href="<?= BASE_URL ?>pages/" class="tdo-link-all">
        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="tdo-bento">

      <a href="<?= BASE_URL ?>pages/<?= safe_html($_tdo_featured['slug']) ?>/" class="text-decoration-none tdo-card tdo-card--featured">
        <div class="tdo-card__inner">
          <span class="tdo-badge">
            <i class="fas fa-bolt me-1"></i> Terbaru
          </span>
          <div class="tdo-card__body">
            <h3 class="tdo-card__title"><?= safe_html($_tdo_featured['title']) ?></h3>
            <span class="tdo-card__date">
              <i class="far fa-calendar me-1"></i>
              <?= date('d M Y', strtotime($_tdo_featured['updated_at'])) ?>
            </span>
          </div>
          <span class="tdo-card__cta">
            Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
          </span>
        </div>
      </a>

      <div class="tdo-stack">
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <a href="<?= BASE_URL ?>pages/<?= safe_html($_tdo_p['slug']) ?>/" class="text-decoration-none tdo-card tdo-card--sm">
          <div class="tdo-card__inner">
            <h6 class="tdo-card__title"><?= safe_html($_tdo_p['title']) ?></h6>
            <div class="d-flex align-items-center justify-content-between mt-auto">
              <span class="tdo-card__date">
                <i class="far fa-calendar me-1"></i>
                <?= date('d M Y', strtotime($_tdo_p['updated_at'])) ?>
              </span>
              <i class="fas fa-arrow-right tdo-card__arrow"></i>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

<style>
.tdo-section {
  background: #f8f9fa;
}
.tdo-label {
  display: block;
  font-size: .75rem;
  font-weight: 600;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: #0d6efd;
  margin-bottom: .25rem;
}
.tdo-heading {
  font-size: 1.6rem;
  font-weight: 700;
  color: #111;
}
.tdo-link-all {
  font-size: .875rem;
  font-weight: 600;
  color: #0d6efd;
  text-decoration: none;
  white-space: nowrap;
  transition: opacity .2s;
}
.tdo-link-all:hover { opacity: .7; }

.tdo-bento {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}
@media (min-width: 768px) {
  .tdo-bento {
    grid-template-columns: 1.6fr 1fr;
    grid-template-rows: auto;
    align-items: stretch;
  }
  .tdo-heading { font-size: 2rem; }
}

.tdo-stack {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.tdo-card {
  border-radius: 1.25rem;
  overflow: hidden;
  display: block;
  transition: transform .2s, box-shadow .2s;
}
.tdo-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 1rem 2rem rgba(0,0,0,.12) !important;
}
.tdo-card__inner {
  height: 100%;
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
}

.tdo-card--featured {
  background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
  min-height: 260px;
}
@media (min-width: 768px) {
  .tdo-card--featured { min-height: 320px; }
}
.tdo-card--featured .tdo-card__title {
  color: #fff;
  font-size: 1.4rem;
  font-weight: 700;
  line-height: 1.3;
}
.tdo-card--featured .tdo-card__date { color: rgba(255,255,255,.7); }
.tdo-card--featured .tdo-card__cta {
  display: inline-block;
  margin-top: 1.5rem;
  padding: .5rem 1.25rem;
  background: rgba(255,255,255,.15);
  color: #fff;
  border-radius: 2rem;
  font-size: .85rem;
  font-weight: 600;
  backdrop-filter: blur(4px);
  border: 1px solid rgba(255,255,255,.2);
  width: fit-content;
}

.tdo-card--sm {
  background: #fff;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
  flex: 1;
}
.tdo-card--sm .tdo-card__inner { min-height: 120px; }
.tdo-card--sm .tdo-card__title {
  color: #111;
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.4;
  margin-bottom: auto;
}

.tdo-badge {
  display: inline-flex;
  align-items: center;
  background: rgba(255,255,255,.2);
  color: #fff;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .05em;
  padding: .3rem .75rem;
  border-radius: 2rem;
  margin-bottom: auto;
  width: fit-content;
  border: 1px solid rgba(255,255,255,.25);
}
.tdo-card__body { margin-top: 1rem; }
.tdo-card__date {
  font-size: .78rem;
  color: #888;
  margin-top: .4rem;
  display: block;
}
.tdo-card__arrow {
  color: #0d6efd;
  font-size: .8rem;
  transition: transform .2s;
}
.tdo-card--sm:hover .tdo-card__arrow {
  transform: translateX(4px);
}
</style>
<?php endif; ?>
