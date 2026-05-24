<?php
$_tdo_stmt = $GLOBALS['pdo']->query("SELECT id, title, slug, html_content, event_date, updated_at FROM pages ORDER BY updated_at DESC LIMIT 3");
$_tdo_pages = $_tdo_stmt->fetchAll();
$_tdo_featured = array_shift($_tdo_pages);

function _tdo_excerpt($html, $limit = 150) {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '…' : $text;
}
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
            <h2 class="text-title"><?= safe_html($_tdo_featured['event_date']) ?></h2>
            <h3 class="tdo-card__title"><?= safe_html($_tdo_featured['title']) ?></h3>
            <p class="tdo-card__excerpt"><?= safe_html(_tdo_excerpt($_tdo_featured['html_content'])) ?></p>
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
            <h5 class="text-title"><?= safe_html($_tdo_p['event_date']) ?></h5>
            <h6 class="tdo-card__title"><?= safe_html($_tdo_p['title']) ?></h6>
            <p class="tdo-card__excerpt tdo-card__excerpt--sm"><?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?></p>
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
  background: #faf5ff;
}
.tdo-label {
  display: block;
  font-size: .75rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #7c3aed;
  margin-bottom: .25rem;
}
.tdo-heading {
  font-size: 1.6rem;
  font-weight: 700;
  color: #1e1b4b;
}
.tdo-link-all {
  font-size: .875rem;
  font-weight: 600;
  color: #7c3aed;
  text-decoration: none;
  white-space: nowrap;
  transition: opacity .2s;
}
.tdo-link-all:hover { opacity: .65; }

.tdo-bento {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}
@media (min-width: 768px) {
  .tdo-bento {
    grid-template-columns: 1.6fr 1fr;
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
  box-shadow: 0 1rem 2rem rgba(124,58,237,.15) !important;
}
.tdo-card__inner {
  height: 100%;
  display: flex;
  flex-direction: column;
  padding: 1.75rem;
}

.tdo-card--featured {
  background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
  min-height: 280px;
  position: relative;
  overflow: hidden;
}
.tdo-card--featured::before {
  content: '';
  position: absolute;
  top: -40px;
  right: -40px;
  width: 180px;
  height: 180px;
  border-radius: 50%;
  background: rgba(255,255,255,.07);
}
.tdo-card--featured::after {
  content: '';
  position: absolute;
  bottom: -60px;
  right: 20px;
  width: 240px;
  height: 240px;
  border-radius: 50%;
  background: rgba(255,255,255,.05);
}
@media (min-width: 768px) {
  .tdo-card--featured { min-height: 340px; }
}
.tdo-card--featured .tdo-card__title {
  color: #fff;
  font-size: 1.4rem;
  font-weight: 700;
  line-height: 1.3;
  margin-bottom: .625rem;
}
.tdo-card--featured .tdo-card__excerpt {
  color: rgba(255,255,255,.75);
  font-size: .9rem;
  line-height: 1.6;
  margin-bottom: .5rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.tdo-card--featured .tdo-card__date {
  color: rgba(255,255,255,.6);
  font-size: .78rem;
}
.tdo-card--featured .tdo-card__cta {
  display: inline-flex;
  align-items: center;
  margin-top: 1.25rem;
  padding: .5rem 1.25rem;
  background: rgba(255,255,255,.15);
  color: #fff;
  border-radius: 2rem;
  font-size: .85rem;
  font-weight: 600;
  border: 1px solid rgba(255,255,255,.25);
  width: fit-content;
  backdrop-filter: blur(4px);
  transition: background .2s;
  position: relative;
  z-index: 1;
}
.tdo-card--featured:hover .tdo-card__cta {
  background: rgba(255,255,255,.25);
}

.tdo-badge {
  display: inline-flex;
  align-items: center;
  background: rgba(255,255,255,.18);
  color: #fff;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .05em;
  padding: .3rem .85rem;
  border-radius: 2rem;
  margin-bottom: auto;
  width: fit-content;
  border: 1px solid rgba(255,255,255,.25);
  position: relative;
  z-index: 1;
}
.tdo-card__body {
  margin-top: 1rem;
  position: relative;
  z-index: 1;
}

.tdo-card--sm {
  background: #fff;
  box-shadow: 0 2px 12px rgba(124,58,237,.08);
  border: 1px solid #ede9fe;
  flex: 1;
}
.tdo-card--sm .tdo-card__inner { min-height: 130px; }
.tdo-card--sm .tdo-card__title {
  color: #1e1b4b;
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.4;
  margin-bottom: .4rem;
}
.tdo-card__excerpt--sm {
  color: #6b7280;
  font-size: .82rem;
  line-height: 1.55;
  margin-bottom: auto;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.tdo-card__date {
  font-size: .78rem;
  color: #a78bfa;
  margin-top: .5rem;
  display: block;
}
.tdo-card--sm .tdo-card__date { color: #a78bfa; }
.tdo-card__arrow {
  color: #7c3aed;
  font-size: .8rem;
  transition: transform .2s;
}
.tdo-card--sm:hover .tdo-card__arrow { transform: translateX(4px); }
</style>
<?php endif; ?>
