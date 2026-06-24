<?php
require_once LIB_PATH . "v-upcoming-events.php";
$page_title = 'Upcoming Events';
?>
<main class="main-content">
  <div class="container">
    <?php if ($_tdo_next || !empty($_tdo_pages)): ?>
    <div class="page-header">
      <?php if ($_tdo_next): ?>
      <div class="col-12">
        <div class="tdop-featured rounded-lg mx-auto" style="max-width:900px">
          <div class="tdop-featured__inner">
            <div class="d-flex justify-content-between align-items-center mb-5">
              <span class="badge badge-accent position-absolute top-0 end-0 m-4 fw-bold">
                <i class="far fa-calendar-check me-1"></i>
                <?= $_tdo_next['event_date'] ? date('d M Y', strtotime($_tdo_next['event_date'])) : '—' ?>
              </span>
              <span class="badge badge-accent position-absolute top-0 start-0 m-4 fw-bold">
                <i class="fas fa-bolt me-1"></i>
                Featured
              </span>
            </div>
            <div class="tdop-featured__body">
              <div class="tdop-featured__content">
                <h1 class="text-white text-center mb-4">
                  <?= safe_html($_tdo_next['title']) ?>
                </h1>
                <p>
                  <?= sanitizeHtml($_tdo_next['html_content']) ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <section>
      <div class="mb-4">
        <h2><span data-bhs="ue.next.title">Ada apa</span> <em data-bhs="ue.next.title.em">Selanjutnya?</em></h2>
        <p class="p-desc">
          <span data-bhs="ue.next.desc">Jangan sampai kelewat! Intip deretan event seru dan festival hits yang bakal hadir dalam waktu dekat.</span> <strong data-bhs="ue.next.cta">Save the date!</strong>
        </p>
      </div>
      <div class="row g-4">
        <?php if (!empty($_tdo_pages)): ?>
        <?php foreach ($_tdo_pages as $_tdo_p): ?>
        <div class="col-12 col-md-6">
          <div class="card card-flatty shadow-none">
            <div class="card-body">
              <h4 class="mb-2">
                <?= safe_html($_tdo_p['title']) ?>
              </h4>
              <p class="text-muted small">
                <?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?>
              </p>
              <div class="d-flex align-items-center justify-content-between mt-auto">
                <span class="tdop-card__date">
                  <i class="far fa-calendar me-2"></i>
                  <?= $_tdo_p['event_date'] ? date('d M Y', strtotime($_tdo_p['event_date'])) : '-' ?>
                </span>
                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>pages/<?= safe_html($_tdo_p['slug']) ?>/">
                  <span data-bhs="btn.more">Selengkapnya</span>
                  <i class="fas fa-angle-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </section>
    <?php if (!empty($_tdo_past)): ?>
    <section>
      <div class="tdopage-section__header">
        <div class="tdopage-section__icon">
          <i class="fas fa-history"></i>
        </div>
        <div>
          <h2 class="tdopage-section__title" data-bhs="ue.ended.title">Telah berakhir</h2>
          <p class="tdopage-section__sub" data-bhs="ue.ended.desc">
            Event yang telah berlalu
          </p>
        </div>
      </div>
      <div class="row g-3">
        <?php foreach ($_tdo_past as $_tdo_p): ?>
        <div class="col-md-6 col-lg-4">
          <div class="tdop-card">
            <div class="tdop-card__inner">
              <h4 class="mb-1"><?= safe_html($_tdo_p['title']) ?></h4>
              <p class="text-muted small">
                <?= safe_html(_tdo_excerpt($_tdo_p['html_content'], 80)) ?>
              </p>
              <div class="d-flex align-items-center justify-content-between mt-auto">
                <span class="tdop-card__date">
                  <i class="far fa-calendar me-2"></i>
                  <?= date('d M Y', strtotime($_tdo_p['event_date'])) ?>
                </span>
                <a href="/pages/<?= safe_html($_tdo_p['slug']) ?>/">
                  <span data-bhs="btn.more">Selengkapnya</span>
                  <i class="fas fa-arrow-right ms-2"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>
    <?php if (!$_tdo_next && empty($_tdo_pages) && empty($_tdo_past)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-file fa-3x mb-3 opacity-50 d-block mx-auto"></i>
      Belum ada konten tersedia.
    </div>
    <?php endif; ?>
  </div>
</main>