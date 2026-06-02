<?php
require_once LIB_PATH . "v-things-to-do.php";
$page_title = 'Things to Do';
?>

<main id="content" class="tdopage-wrap">
  <div class="container">
    <section id="page-things-to-do">
      <h1 class="uppercase">Things to Do</h1>
      <p class="lead">Eksplorasi kuliner, budaya, dan life style di Kota Kembang</p>
    </section>

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
    <?php if (!$kuliner_items && empty($budaya_items)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-file fa-3x mb-3 opacity-50 d-block"></i>
      Belum ada konten tersedia.
    </div>
    <?php endif; ?>

  </div>
</main>