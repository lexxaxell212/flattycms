<?php
require_once LIB_PATH . "v-things-to-do.php";

$page_title = 'Things to Do';
?>

<main id="content" class="container-fluid tdo-page">
  <div class="container">

    <div class="tdo-page-header">
      <span class="tdo-label">Bandung</span>
      <h1 class="tdo-page-heading">Things to Do</h1>
      <p class="tdo-page-sub">Eksplorasi event, kuliner, dan budaya terbaik di Kota Kembang</p>
    </div>

    <?php if ($_tdo_next || !empty($_tdo_pages)): ?>
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
      <?php if ($_tdo_next): ?>
      <a href="/pages/<?= safe_html($_tdo_next['slug']) ?>/" class="text-decoration-none d-block mb-4">
        <div class="tdo-featured">
          <div class="tdo-featured__inner">
            <span class="tdo-badge"><i class="fas fa-bolt me-1"></i>Terbaru</span>
            <div class="tdo-featured__body">
              <h5 class="text-title"><?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?></h5>
              <h3 class="tdo-featured__title"><?= safe_html($_tdo_next['title']) ?></h3>
              <p class="tdo-featured__excerpt"><?= safe_html(_tdo_excerpt($_tdo_next['html_content'])) ?></p>
              <span class="tdo-featured__date"><i class="far fa-calendar me-1"></i><?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?></span>
            </div>
            <span class="tdo-featured__cta">Lihat Selengkapnya <i class="fas fa-arrow-right ms-1"></i></span>
          </div>
          <div class="tdo-featured__deco"><i class="fas fa-compass"></i></div>
        </div>
      </a>
      <?php endif; ?>
      <?php if (!empty($_tdo_pages)): ?>
      <div class="row g-3">
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <div class="col-md-6 col-lg-4">
          <a href="/pages/<?= safe_html($_tdo_p['slug']) ?>/" class="text-decoration-none">
            <div class="tdo-card h-100">
              <div class="tdo-card__body">
                <h6 class="tdo-card__title"><?= $_tdo_p['event_date'] ? date('d M Y', strtotime($_tdo_p['event_date'])) : '—' ?></h6>
                <h6 class="tdo-card__title"><?= safe_html($_tdo_p['title']) ?></h6>
                <p class="tdo-card__excerpt"><?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?></p>
              </div>
              <div class="tdo-card__footer">
                <span class="tdo-card__date"><i class="far fa-calendar me-1"></i><?= $_tdo_p['event_date'] ? date('d M Y', strtotime($_tdo_p['event_date'])) : '—' ?></span>
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

    <?php if (!$_tdo_next && empty($_tdo_pages) && empty($kuliner_items) && empty($budaya_items)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-file fa-3x mb-3 opacity-50 d-block"></i>
      Belum ada konten tersedia.
    </div>
    <?php endif; ?>

  </div>
</main>
