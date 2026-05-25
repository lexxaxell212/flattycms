<?php
require_once LIB_PATH . "v-things-to-do.php";
?>

<?php if ($_tdo_next): ?>
<section class="container-fluid py-5 tdo-section">
  <div class="container">

    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <span class="tdo-label">Things to Do</span>
        <h2 class="tdo-heading mb-0">Event & Info Terkini</h2>
      </div>
      <a href="/things-to-do" class="tdo-link-all">
        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="tdo-bento">

      <div class="tdo-card tdo-card--featured">
        <div class="tdo-card__inner">
          <span class="tdo-badge"><i class="fas fa-bolt me-1"></i> Terbaru</span>
          <div class="tdo-card__body">
            <h3 class="tdo-card__title"><?= safe_html($_tdo_next['title']) ?></h3>
            <p class="tdo-card__excerpt"><?= safe_html(_tdo_excerpt($_tdo_next['html_content'])) ?></p>
            <span class="tdo-card__date">
              <i class="far fa-calendar me-1"></i>
              <?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?>
            </span>
          </div>
          <a href="/pages/<?= safe_html($_tdo_next['slug']) ?>/" class="tdo-card__cta">Selengkapnya <i
          class="fas fa-arrow-right ms-1"></i></a>
        </div>
      </div>

      <div class="tdo-stack">
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <a href="<?= BASE_URL ?>pages/<?= safe_html($_tdo_p['slug']) ?>/" class="text-decoration-none tdo-card tdo-card--sm">
          <div class="tdo-card__inner">
            <h5 class="tdo-card__title"><?= safe_html($_tdo_p['title']) ?></h5>
            <p class="tdo-card__excerpt tdo-card__excerpt--sm"><?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?></p>
            <div class="d-flex align-items-center justify-content-between mt-auto">
              <span class="tdo-card__date">
                <i class="far fa-calendar me-1"></i>
                <?= $_tdo_p['event_date'] ? date('d M Y', strtotime($_tdo_p['event_date'])) : '—' ?>
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
<?php endif; ?>
