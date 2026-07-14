<?php
require_once LIB_PATH . "v-review.php";
?>
<?php if (!empty($_rev_items)): ?>
<div class="container">
 <section class="rev-section">
  <div class="d-flex align-items-end justify-content-between mb-4">
   <div>
    <span class="text-eyebrow" data-bhs="r.eyebrow">ULASAN</span>
    <h2><em class="styled" data-bhs="r.title">Cerita Pengunjung</em></h2>
   </div>
   <a href="/gallery" class="link-all">
    <span data-bhs="btn.all">Lihat Semua</span> <i class="fas fa-arrow-right ms-2"></i>
   </a>
  </div>
  <div class="rev-grid">
   <?php foreach ($_rev_items as $i => $_rev): ?>
   <div class="rev-card mb-3">
    <div class="rev-card__top">
     <span class="rev-card__quote">"</span>
     <div class="rev-card__stars">
      <?php for ($s = 1; $s <= 5; $s++): ?>
      <i class="fa-<?= $s <= $_rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
      <?php endfor; ?>
     </div>
    </div>
    <?php if (!empty($_rev['judul'])): ?>
    <div class="rev-card__title">
     <?= safe_html($_rev['judul']) ?>
    </div>
    <?php endif; ?>
    <p class="rev-card__text">
     <?= safe_html(mb_substr($_rev['cerita'], 0, 300) .
      (mb_strlen($_rev['cerita']) > 300 ? '…' : '')) ?>
    </p>
    <div class="rev-card__author">
     <img src="<?= !empty($_rev['avatar']) ? safe_html($_rev['avatar']) : '/assets/images/avatar.png' ?>"
     class="rev-card__avatar"
     onerror="this.src='/assets/images/avatar.png'"
     alt="<?= safe_html($_rev['user_name']) ?>">
     <div>
      <div class="rev-card__name fw-medium">
       <?= safe_html($_rev['user_name']) ?>
      </div>
      <div class="rev-card__poi text-muted">
       <i class="fas fa-location-dot me-1"></i><?=
       safe_html($_rev['poi_name']) ?>
      </div>
     </div>
    </div>
   </div>
   <?php endforeach; ?>
  </div>
 </section>
</div>
<?php endif; ?>