<?php
require_once LIB_PATH . "v-upcoming-events.php";
?>
<?php if ($_tdo_next): ?>
<div class="container">
  <section id="upcoming-event">
    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <span class="text-eyebrow">Highlight</span>
        <h2 class="text-sub-hero">Upcoming Events</h2>
      </div>
      <a href="/upcoming-events" class="link-all">
        Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
      </a>
    </div>
    <div class="tdop-bento">
      <div class="tdop-featured">
        <div class="tdop-featured__inner">
          <div class="tdop-badge">
            <i class="fas fa-bolt me-2"></i>
            <span data-rotate="Event Terdekat|Segera Hadir|Cek Sekarang">Event Terdekat</span>
          </div>
          <div class="tdop-featured__body">
            <h3 class="tdop-featured__title"><?= safe_html($_tdo_next['title'])
            ?></h3>
            <p class="tdop-featured__excerpt"><?= safe_html(_tdo_excerpt($_tdo_next['html_content'])) ?></p>
            <span class="tdop-featured__date">
              <i class="far fa-calendar me-1"></i>
              <?= $_tdo_next['event_date'] ? date('d M Y',
              strtotime($_tdo_next['event_date'])) : '—' ?>
            </span>
          </div>
          <a href="/pages/<?= safe_html($_tdo_next['slug']) ?>/" class="tdop-featured__cta">
            Selengkapnya <i class="fas fa-angle-right ms-2"></i>
          </a>
        </div>
      </div>
      <div class="tdop-stack">
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <div class="tdop-card">
          <div class="tdop-card__inner">
            <h4 class="mb-1"><?= safe_html($_tdo_p['title']) ?></h4>
            <p class="text-muted small"><?=
            safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?></p>
            <div class="d-flex align-items-center justify-content-between mt-auto">
              <span class="tdop-card__date">
                <i class="far fa-calendar me-1"></i>
                <?= $_tdo_p['event_date'] ? date('d M Y',
                strtotime($_tdo_p['event_date'])) : '—' ?>
              </span>
              <a href="<?= BASE_URL ?>pages/<?= safe_html($_tdo_p['slug']) ?>/">
              Selengkapnya
              <i class="fas fa-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</div>
<?php endif; ?>