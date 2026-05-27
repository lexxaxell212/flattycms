<?php
require_once LIB_PATH . "v-things-to-do.php";

$page_title = 'Things to Do';
?>

<main id="content" class="tdopage-wrap">
  <div class="container">

    <div class="tdopage-header">
      <span class="tdopage-label">Bandung</span>
      <h1 class="tdopage-heading">Things to Do</h1>
      <p class="tdopage-sub">Eksplorasi event, kuliner, dan budaya terbaik di Kota Kembang</p>
    </div>

    <!-- ── Event & Info Terkini ── -->
    <?php if ($_tdo_next || !empty($_tdo_pages)): ?>
    <div class="tdopage-section">
      <div class="tdopage-section__header">
        <div class="tdopage-section__icon" style="--ic:#7c3aed">
          <i class="fas fa-bolt"></i>
        </div>
        <div>
          <h2 class="tdopage-section__title">Event & Info Terkini</h2>
          <p class="tdopage-section__sub">Update terbaru seputar Bandung</p>
        </div>
      </div>

      <?php if ($_tdo_next): ?>
      <div class="mb-4">
        <div class="tdopage-featured">
          <div class="tdopage-featured__inner">
            <span class="tdopage-badge"><i class="fas fa-bolt me-1"></i>Terbaru</span>
            <div class="tdopage-featured__body">
              <h5 class="tdopage-featured__date-label">
                <?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?>
              </h5>
              <h3 class="tdopage-featured__title"><?= safe_html($_tdo_next['title']) ?></h3>
              <p class="tdopage-featured__excerpt"><?= safe_html(_tdo_excerpt($_tdo_next['html_content'])) ?></p>
              <span class="tdopage-featured__date">
                <i class="far fa-calendar me-1"></i>
                <?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?>
              </span>
            </div>
            <a href="/pages/<?= safe_html($_tdo_next['slug']) ?>/" class="tdopage-featured__cta">
              Lihat Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!empty($_tdo_pages)): ?>
      <div class="row g-3">
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <div class="col-md-6 col-lg-4">
          <a href="/pages/<?= safe_html($_tdo_p['slug']) ?>/" class="text-decoration-none h-100 d-block">
            <div class="tdopage-card h-100">
              <div class="tdopage-card__body">
                <h5 class="tdopage-card__title"><?= safe_html($_tdo_p['title']) ?></h5>
                <p class="tdopage-card__excerpt"><?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?></p>
              </div>
              <div class="tdopage-card__footer">
                <span class="tdopage-card__date">
                  <i class="far fa-calendar me-1"></i>
                  <?= $_tdo_p['event_date'] ? date('d M Y', strtotime($_tdo_p['event_date'])) : '—' ?>
                </span>
                <span class="tdopage-card__read">Baca <i class="fas fa-arrow-right ms-1 tdopage-card__arrow"></i></span>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- ── Kuliner ── -->
    <?php if (!empty($kuliner_items)): ?>
    <div class="tdopage-section">
      <div class="tdopage-section__header">
        <div class="tdopage-section__icon" style="--ic:#f59e0b">
          <i class="fas fa-utensils"></i>
        </div>
        <div>
          <h2 class="tdopage-section__title">Kuliner</h2>
          <p class="tdopage-section__sub">Cita rasa autentik khas Bandung</p>
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
          <div class="tdopage-item-card h-100">
            <div class="tdopage-item-card__img">
              <img src="<?= $image ?>" alt="<?= $title ?>" onerror="this.src='<?= BASE_URL ?>uploads/default.jpg'">
              <div class="tdopage-item-card__overlay"></div>
            </div>
            <div class="tdopage-item-card__body">
              <h5 class="tdopage-item-card__title"><?= $title ?></h5>
              <?php if ($excerpt): ?>
              <p class="tdopage-item-card__excerpt"><?= $excerpt ?></p>
              <?php endif; ?>
              <a href="<?= $link ?>" class="tdopage-item-card__cta" style="--cta:#ac94fa">
                Lihat <i class="fas fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── Wisata & Budaya ── -->
    <?php if (!empty($budaya_items)): ?>
    <div class="tdopage-section">
      <div class="tdopage-section__header">
        <div class="tdopage-section__icon" style="--ic:#818cf8">
          <i class="fas fa-landmark"></i>
        </div>
        <div>
          <h2 class="tdopage-section__title">Wisata & Budaya</h2>
          <p class="tdopage-section__sub">Warisan seni dan budaya Kota Kembang</p>
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
          <div class="tdopage-item-card h-100">
            <div class="tdopage-item-card__img">
              <img src="<?= $image ?>" alt="<?= $title ?>" onerror="this.src='<?= BASE_URL ?>uploads/default.jpg'">
              <div class="tdopage-item-card__overlay"></div>
            </div>
            <div class="tdopage-item-card__body">
              <h5 class="tdopage-item-card__title"><?= $title ?></h5>
              <?php if ($excerpt): ?>
              <p class="tdopage-item-card__excerpt"><?= $excerpt ?></p>
              <?php endif; ?>
              <a href="<?= $link ?>" class="tdopage-item-card__cta" style="--cta:#ac94fa">
                Lihat <i class="fas fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── Empty state ── -->
    <?php if (!$_tdo_next && empty($_tdo_pages) && empty($kuliner_items) && empty($budaya_items)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-file fa-3x mb-3 opacity-50 d-block"></i>
      Belum ada konten tersedia.
    </div>
    <?php endif; ?>

  </div>
</main>
