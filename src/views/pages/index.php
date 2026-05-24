<?php
$stmt = $GLOBALS['pdo']->query("SELECT id, title, slug, html_content, updated_at FROM pages ORDER BY updated_at DESC LIMIT 10");
$pages = $stmt->fetchAll();
$featured = array_shift($pages);

$kuliner_items = $GLOBALS['pdo']->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('kuliner') ORDER BY id DESC")->fetchAll();
$budaya_items  = $GLOBALS['pdo']->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('wisata_budaya') ORDER BY id DESC")->fetchAll();

$page_title = 'Things to Do';

function _tdo_excerpt($html, $limit = 150) {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '…' : $text;
}
?>

<main id="content" class="container-fluid tdo-page">
  <div class="container">

    <div class="tdo-page-header">
      <span class="tdo-label">Bandung</span>
      <h1 class="tdo-page-heading">Things to Do</h1>
      <p class="tdo-page-sub">Eksplorasi event, kuliner, dan budaya terbaik di Kota Kembang</p>
    </div>

    <?php if ($featured || !empty($pages)): ?>
    <div class="tdo-section">
      <div class="tdo-section__header">
        <div class="tdo-section__icon" style="--ic:#7c3aed">
          <i class="fas fa-bolt"></i>
        </div>
        <div>
          <h2 class="tdo-section__title">Event & Info Terkini</h2>
          <p class="tdo-section__sub">Update terbaru seputar Bandung</p>
        </div>
      </div>

      <?php if ($featured): ?>
      <a href="/pages/<?= safe_html($featured['slug']) ?>/" class="text-decoration-none d-block mb-4">
        <div class="tdo-featured">
          <div class="tdo-featured__inner">
            <span class="tdo-badge"><i class="fas fa-bolt me-1"></i>Terbaru</span>
            <div class="tdo-featured__body">
              <h3 class="tdo-featured__title"><?= safe_html($featured['title']) ?></h3>
              <p class="tdo-featured__excerpt"><?= safe_html(_tdo_excerpt($featured['html_content'])) ?></p>
              <span class="tdo-featured__date"><i class="far fa-calendar me-1"></i><?= date('d M Y', strtotime($featured['updated_at'])) ?></span>
            </div>
            <span class="tdo-featured__cta">Lihat Selengkapnya <i class="fas fa-arrow-right ms-1"></i></span>
          </div>
          <div class="tdo-featured__deco"><i class="fas fa-compass"></i></div>
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
                <p class="tdo-card__excerpt"><?= safe_html(_tdo_excerpt($p['html_content'], 80)) ?></p>
              </div>
              <div class="tdo-card__footer">
                <span class="tdo-card__date"><i class="far fa-calendar me-1"></i><?= date('d M Y', strtotime($p['updated_at'])) ?></span>
                <span class="tdo-card__read">Baca <i class="fas fa-arrow-right ms-1 tdo-card__arrow"></i></span>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($kuliner_items)): ?>
    <div class="tdo-section">
      <div class="tdo-section__header">
        <div class="tdo-section__icon" style="--ic:#f59e0b">
          <i class="fas fa-utensils"></i>
        </div>
        <div>
          <h2 class="tdo-section__title">Kuliner</h2>
          <p class="tdo-section__sub">Cita rasa autentik khas Bandung</p>
        </div>
      </div>
      <div class="row g-3">
        <?php foreach ($kuliner_items as $item):
          $image   = !empty($item['image']) ? (strpos($item['image'], 'http') === 0 ? $item['image'] : BASE_UPLOAD_URL . $item['image']) : BASE_URL . 'uploads/default.jpg';
          $title   = htmlspecialchars($item['title'] ?? 'Untitled');
          $excerpt = htmlspecialchars(substr($item['excerpt'] ?? '', 0, 100));
          $link    = htmlspecialchars($item['button_link'] ?? '#');
        ?>
        <div class="col-md-6 col-lg-4">
          <a href="<?= $link ?>" class="text-decoration-none">
            <div class="tdo-item-card h-100">
              <div class="tdo-item-card__img">
                <img src="<?= $image ?>" alt="<?= $title ?>" onerror="this.src='<?= BASE_URL ?>uploads/default.jpg'">
                <div class="tdo-item-card__overlay"></div>
              </div>
              <div class="tdo-item-card__body">
                <h6 class="tdo-item-card__title"><?= $title ?></h6>
                <?php if ($excerpt): ?>
                <p class="tdo-item-card__excerpt"><?= $excerpt ?></p>
                <?php endif; ?>
                <span class="tdo-item-card__cta" style="--cta:#f59e0b">
                  Lihat <i class="fas fa-arrow-right ms-1"></i>
                </span>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($budaya_items)): ?>
    <div class="tdo-section">
      <div class="tdo-section__header">
        <div class="tdo-section__icon" style="--ic:#818cf8">
          <i class="fas fa-landmark"></i>
        </div>
        <div>
          <h2 class="tdo-section__title">Wisata & Budaya</h2>
          <p class="tdo-section__sub">Warisan seni dan budaya Kota Kembang</p>
        </div>
      </div>
      <div class="row g-3">
        <?php foreach ($budaya_items as $item):
          $image   = !empty($item['image']) ? (strpos($item['image'], 'http') === 0 ? $item['image'] : BASE_UPLOAD_URL . $item['image']) : BASE_URL . 'uploads/default.jpg';
          $title   = htmlspecialchars($item['title'] ?? 'Untitled');
          $excerpt = htmlspecialchars(substr($item['excerpt'] ?? '', 0, 100));
          $link    = htmlspecialchars($item['button_link'] ?? '#');
        ?>
        <div class="col-md-6 col-lg-4">
          <a href="<?= $link ?>" class="text-decoration-none">
            <div class="tdo-item-card h-100">
              <div class="tdo-item-card__img">
                <img src="<?= $image ?>" alt="<?= $title ?>" onerror="this.src='<?= BASE_URL ?>uploads/default.jpg'">
                <div class="tdo-item-card__overlay"></div>
              </div>
              <div class="tdo-item-card__body">
                <h6 class="tdo-item-card__title"><?= $title ?></h6>
                <?php if ($excerpt): ?>
                <p class="tdo-item-card__excerpt"><?= $excerpt ?></p>
                <?php endif; ?>
                <span class="tdo-item-card__cta" style="--cta:#818cf8">
                  Lihat <i class="fas fa-arrow-right ms-1"></i>
                </span>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!$featured && empty($pages) && empty($kuliner_items) && empty($budaya_items)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-file fa-3x mb-3 opacity-50 d-block"></i>
      Belum ada konten tersedia.
    </div>
    <?php endif; ?>

  </div>
</main>

<style>
.tdo-page { background: #faf5ff; min-height: 80vh; padding-bottom: 5rem; }

.tdo-page-header {
  padding: 3.5rem 0 3rem;
  text-align: center;
}
.tdo-label {
  display: block;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: #7c3aed;
  margin-bottom: .25rem;
}
.tdo-page-heading {
  font-size: clamp(2rem, 5vw, 3.2rem);
  font-weight: 800;
  color: #1e1b4b;
  letter-spacing: -.03em;
  margin-bottom: .5rem;
}
.tdo-page-sub { color: #6b7280; font-size: 1rem; margin: 0; }

.tdo-section {
  margin-bottom: 4rem;
}
.tdo-section__header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.75rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #ede9fe;
}
.tdo-section__icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: color-mix(in srgb, var(--ic) 12%, transparent);
  color: var(--ic);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  flex-shrink: 0;
}
.tdo-section__title {
  font-size: 1.3rem;
  font-weight: 800;
  color: #1e1b4b;
  margin: 0;
  letter-spacing: -.02em;
}
.tdo-section__sub { font-size: .82rem; color: #9ca3af; margin: 0; }

.tdo-featured {
  background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
  border-radius: 1.25rem;
  display: flex;
  min-height: 220px;
  position: relative;
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
}
.tdo-featured:hover { transform: translateY(-4px); box-shadow: 0 1.25rem 2.5rem rgba(124,58,237,.25); }
.tdo-featured__inner { flex: 1; display: flex; flex-direction: column; padding: 2rem 2.5rem; position: relative; z-index: 1; }
.tdo-featured__deco { position: absolute; top: 0; right: 0; height: 100%; display: flex; align-items: center; padding-right: 2.5rem; font-size: 7rem; color: rgba(255,255,255,.07); z-index: 0; }
.tdo-badge { display: inline-flex; align-items: center; background: rgba(255,255,255,.18); color: #fff; font-size: .72rem; font-weight: 700; padding: .3rem .85rem; border-radius: 2rem; width: fit-content; border: 1px solid rgba(255,255,255,.25); margin-bottom: auto; }
.tdo-featured__body { margin-top: 1rem; }
.tdo-featured__title { color: #fff; font-size: 1.4rem; font-weight: 700; line-height: 1.3; margin-bottom: .5rem; }
.tdo-featured__excerpt { color: rgba(255,255,255,.75); font-size: .875rem; line-height: 1.6; margin-bottom: .5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.tdo-featured__date { font-size: .78rem; color: rgba(255,255,255,.6); display: block; margin-bottom: 1rem; }
.tdo-featured__cta { display: inline-flex; align-items: center; padding: .5rem 1.25rem; background: rgba(255,255,255,.15); color: #fff; border-radius: 2rem; font-size: .85rem; font-weight: 600; border: 1px solid rgba(255,255,255,.25); width: fit-content; transition: background .2s; }
.tdo-featured:hover .tdo-featured__cta { background: rgba(255,255,255,.25); }

.tdo-card { background: #fff; border-radius: .875rem; border: 1px solid #ede9fe; box-shadow: 0 2px 12px rgba(124,58,237,.07); display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; overflow: hidden; }
.tdo-card:hover { transform: translateY(-4px); box-shadow: 0 .75rem 1.75rem rgba(124,58,237,.13); }
.tdo-card__body { padding: 1.25rem 1.25rem .75rem; flex: 1; }
.tdo-card__title { color: #1e1b4b; font-size: 1rem; font-weight: 600; line-height: 1.4; margin-bottom: .5rem; }
.tdo-card__excerpt { color: #6b7280; font-size: .82rem; line-height: 1.55; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.tdo-card__footer { padding: .75rem 1.25rem; border-top: 1px solid #f5f3ff; display: flex; align-items: center; justify-content: space-between; }
.tdo-card__date { font-size: .75rem; color: #a78bfa; }
.tdo-card__read { font-size: .8rem; font-weight: 600; color: #7c3aed; }
.tdo-card__arrow { font-size: .7rem; transition: transform .2s; }
.tdo-card:hover .tdo-card__arrow { transform: translateX(4px); }

.tdo-item-card { background: #fff; border-radius: 1rem; border: 1px solid #ede9fe; overflow: hidden; display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; }
.tdo-item-card:hover { transform: translateY(-4px); box-shadow: 0 .75rem 1.75rem rgba(0,0,0,.1); }
.tdo-item-card__img { position: relative; padding-top: 60%; overflow: hidden; }
.tdo-item-card__img img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.tdo-item-card:hover .tdo-item-card__img img { transform: scale(1.05); }
.tdo-item-card__overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.3), transparent); }
.tdo-item-card__body { padding: 1.125rem; flex: 1; display: flex; flex-direction: column; gap: .4rem; }
.tdo-item-card__title { font-size: .95rem; font-weight: 700; color: #1e1b4b; line-height: 1.3; margin: 0; }
.tdo-item-card__excerpt { font-size: .8rem; color: #6b7280; line-height: 1.55; margin: 0; flex: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.tdo-item-card__cta { font-size: .8rem; font-weight: 700; color: var(--cta); margin-top: auto; }
</style>
