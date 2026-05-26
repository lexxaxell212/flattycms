<?php
require_once LIB_PATH . "v-things-to-do.php";
?>

<?php if ($_tdo_next): ?>
<section class="container-fluid py-5 tdop-section">
  <div class="container">

    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <span class="tdop-label">Things to Do</span>
        <h2 class="tdop-heading mb-0">Event & Info Terkini</h2>
      </div>
      <a href="/things-to-do" class="tdop-link-all">
        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="tdop-bento">

      <!-- Featured card: event terdekat berdasar event_date ASC -->
      <div class="tdop-featured">
        <div class="tdop-featured__inner">
          <span class="tdop-badge"><i class="fas fa-bolt me-1"></i> Terbaru</span>
          <div class="tdop-featured__body">
            <h3 class="tdop-featured__title"><?= safe_html($_tdo_next['title']) ?></h3>
            <p class="tdop-featured__excerpt"><?= safe_html(_tdo_excerpt($_tdo_next['html_content'])) ?></p>
            <span class="tdop-featured__date">
              <i class="far fa-calendar me-1"></i>
              <?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?>
            </span>
          </div>
          <a href="/pages/<?= safe_html($_tdo_next['slug']) ?>/" class="tdop-featured__cta">
            Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Stack: 6 event lainnya -->
      <div class="tdop-stack">
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <a href="<?= BASE_URL ?>pages/<?= safe_html($_tdo_p['slug']) ?>/" class="text-decoration-none tdop-card">
          <div class="tdop-card__inner">
            <h5 class="tdop-card__title"><?= safe_html($_tdo_p['title']) ?></h5>
            <p class="tdop-card__excerpt"><?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?></p>
            <div class="d-flex align-items-center justify-content-between mt-auto">
              <span class="tdop-card__date">
                <i class="far fa-calendar me-1"></i>
                <?= $_tdo_p['event_date'] ? date('d M Y', strtotime($_tdo_p['event_date'])) : '—' ?>
              </span>
              <i class="fas fa-arrow-right tdop-card__arrow"></i>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>
<?php endif; ?>
