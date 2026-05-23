<?php
$stmt = $GLOBALS['pdo']->query("SELECT id, title, slug, html_content, updated_at FROM pages ORDER BY updated_at DESC LIMIT 10");
$pages = $stmt->fetchAll();

$featured = array_shift($pages);

$page_title = 'Things to Do';

function _pages_excerpt($html, $limit = 150) {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '…' : $text;
}
?>

<main id="content" class="container-fluid py-5 tdo-full-section">
  <div class="container">

    <div class="mb-5">
      <span class="tdo-label">Bandung</span>
      <h2 class="tdo-heading mb-1">Things to Do</h2>
      <p class="tdo-sub mb-0">Temukan aktivitas dan informasi menarik</p>
    </div>

    <?php if ($featured): ?>
    <a href="/pages/<?= safe_html($featured['slug']) ?>/" class="text-decoration-none d-block mb-4">
      <div class="tdo-featured position-relative overflow-hidden">
        <div class="tdo-featured__inner">
          <span class="tdo-badge">
            <i class="fas fa-bolt me-1"></i> Terbaru
          </span>
          <div class="tdo-featured__body">
            <h3 class="tdo-featured__title"><?= safe_html($featured['title']) ?></h3>
            <p class="tdo-featured__excerpt"><?= safe_html(_pages_excerpt($featured['html_content'])) ?></p>
            <span class="tdo-featured__date">
              <i class="far fa-calendar me-1"></i>
              <?= date('d M Y', strtotime($featured['updated_at'])) ?>
            </span>
          </div>
          <span class="tdo-featured__cta">
            Lihat Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
          </span>
        </div>
        <div class="tdo-featured__deco">
          <i class="fas fa-compass"></i>
        </div>
      </div>
    </a>
    <?php endif; ?>

    <?php if (!empty($pages)): ?>
    <div class="row g-3">
      <?php foreach ($pages as $p): ?>
      <div class="col-md-6 col-lg-4">
        <a href="/pages/<?= safe_html($p['slug']) ?>/" class="text-decoration-none">
          <div class="tdo-card h-100">
            <div class="tdo-card__body">
              <h6 class="tdo-card__title"><?= safe_html($p['title']) ?></h6>
              <p class="tdo-card__excerpt"><?= safe_html(_pages_excerpt($p['html_content'], 80)) ?></p>
            </div>
            <div class="tdo-card__footer">
              <span class="tdo-card__date">
                <i class="far fa-calendar me-1"></i>
                <?= date('d M Y', strtotime($p['updated_at'])) ?>
              </span>
              <span class="tdo-card__read">
                Baca <i class="fas fa-arrow-right ms-1 tdo-card__arrow"></i>
              </span>
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
.tdo-full-section { background: #faf5ff; min-height: 80vh; }

.tdo-label {
  display: block;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: #7c3aed;
  margin-bottom: .25rem;
}
.tdo-heading {
  font-size: 2rem;
  font-weight: 800;
  color: #1e1b4b;
  letter-spacing: -.02em;
}
.tdo-sub { color: #6b7280; font-size: .95rem; }

.tdo-featured {
  background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
  border-radius: 1.25rem;
  display: flex;
  min-height: 240px;
  transition: transform .2s, box-shadow .2s;
}
.tdo-featured:hover {
  transform: translateY(-4px);
  box-shadow: 0 1.25rem 2.5rem rgba(124,58,237,.25) !important;
}
.tdo-featured__inner {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 2rem 2.5rem;
  position: relative;
  z-index: 1;
}
.tdo-featured__deco {
  position: absolute;
  top: 0;
  right: 0;
  height: 100%;
  display: flex;
  align-items: center;
  padding-right: 2.5rem;
  font-size: 7rem;
  color: rgba(255,255,255,.07);
  z-index: 0;
  line-height: 1;
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
  width: fit-content;
  border: 1px solid rgba(255,255,255,.25);
  margin-bottom: auto;
}
.tdo-featured__body { margin-top: 1rem; }
.tdo-featured__title {
  color: #fff;
  font-size: 1.5rem;
  font-weight: 700;
  line-height: 1.3;
  margin-bottom: .625rem;
}
.tdo-featured__excerpt {
  color: rgba(255,255,255,.75);
  font-size: .9rem;
  line-height: 1.6;
  margin-bottom: .5rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.tdo-featured__date {
  font-size: .78rem;
  color: rgba(255,255,255,.6);
  display: block;
  margin-bottom: 1.25rem;
}
.tdo-featured__cta {
  display: inline-flex;
  align-items: center;
  padding: .5rem 1.25rem;
  background: rgba(255,255,255,.15);
  color: #fff;
  border-radius: 2rem;
  font-size: .85rem;
  font-weight: 600;
  border: 1px solid rgba(255,255,255,.25);
  width: fit-content;
  transition: background .2s;
}
.tdo-featured:hover .tdo-featured__cta { background: rgba(255,255,255,.25); }

.tdo-card {
  background: #fff;
  border-radius: .875rem;
  border: 1px solid #ede9fe;
  box-shadow: 0 2px 12px rgba(124,58,237,.07);
  display: flex;
  flex-direction: column;
  transition: transform .2s, box-shadow .2s;
  overflow: hidden;
}
.tdo-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 .75rem 1.75rem rgba(124,58,237,.13) !important;
}
.tdo-card__body { padding: 1.25rem 1.25rem .75rem; flex: 1; }
.tdo-card__title {
  color: #1e1b4b;
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.4;
  margin-bottom: .5rem;
}
.tdo-card__excerpt {
  color: #6b7280;
  font-size: .82rem;
  line-height: 1.55;
  margin-bottom: 0;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.tdo-card__footer {
  padding: .75rem 1.25rem;
  border-top: 1px solid #f5f3ff;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.tdo-card__date { font-size: .75rem; color: #a78bfa; }
.tdo-card__read { font-size: .8rem; font-weight: 600; color: #7c3aed; }
.tdo-card__arrow {
  font-size: .7rem;
  transition: transform .2s;
}
.tdo-card:hover .tdo-card__arrow { transform: translateX(4px); }
</style>
