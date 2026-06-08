<?php
require_once LIB_PATH . "v-upcoming-events.php";
$page_title = 'Upcoming Events';
?>
<main class="main-content">
  <div class="tdopage-wrap">
    <div class="container">
      <section id="upcoming-events-pages">
        <h1 class="uppercase">Upcoming Events</h1>
        <p class="lead">Events di Bandung</p>
      </section>
  
      <!-- ── Event akan datang  ── -->
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
              <span class="tdopage-badge"><i class="fas fa-bolt me-1"></i>Akan
              Datang</span>
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
                Selengkapnya <i class="fas fa-angle-right ms-1"></i>
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
      
      <!-- ── Event berlalu ── -->
      <?php if (!empty($_tdo_past)): ?>
      <div class="tdopage-section">
        <div class="tdopage-section__header">
          <div class="tdopage-section__icon" style="--ic:#9ca3af">
            <i class="fas fa-history"></i>
          </div>
          <div>
            <h2 class="tdopage-section__title">Event Berlalu</h2>
            <p class="tdopage-section__sub">Event yang sudah selesai</p>
          </div>
        </div>
        <div class="row g-3">
          <?php foreach ($_tdo_past as $_tdo_p): ?>
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
                    <?= date('d M Y', strtotime($_tdo_p['event_date'])) ?>
                  </span>
                  <span class="tdopage-card__read">Baca <i class="fas fa-arrow-right ms-1 tdopage-card__arrow"></i></span>
                </div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
  
      <!-- ── Empty state ── -->
       <?php if (!$_tdo_next && empty($_tdo_pages) && empty($_tdo_past)): ?>
      <div class="text-center py-5 text-muted">
        <i class="fas fa-file fa-3x mb-3 opacity-50 d-block"></i>
        Belum ada konten tersedia.
      </div>
      <?php endif; ?>
  
    </div>
  </div>
</main>