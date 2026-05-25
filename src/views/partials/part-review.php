<?php
require_once LIB_PATH . "v-review.php";
?>

<?php if (!empty($_rev_items)): ?>
<section class="rev-section container-fluid">
  <div class="container">

    <div class="rev-header">
      <div>
        <span class="rev-eyebrow">
          <span class="rev-eyebrow__dot"></span>
          Dari Traveler
        </span>
        <h2 class="rev-heading">Cerita dari <em>Komunitas</em></h2>
      </div>
      <a href="gallery/" class="rev-link-all">
        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="rev-grid">
      <?php foreach ($_rev_items as $i => $_rev): ?>
      <div class="rev-card">
        <div class="rev-card__top">
          <span class="rev-card__quote">"</span>
          <div class="rev-card__stars">
            <?php for ($s = 1; $s <= 5; $s++): ?>
              <i class="fa-<?= $s <= $_rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
            <?php endfor; ?>
          </div>
        </div>
        <?php if (!empty($_rev['judul'])): ?>
        <div class="rev-card__title"><?= safe_html($_rev['judul']) ?></div>
        <?php endif; ?>
        <p class="rev-card__text">
          <?= safe_html(mb_substr($_rev['cerita'], 0, 160) . (mb_strlen($_rev['cerita']) > 160 ? '…' : '')) ?>
        </p>
        <div class="rev-card__author">
          <img src="<?= !empty($_rev['avatar']) ? safe_html($_rev['avatar']) : '/uploads/default.jpg' ?>"
               class="rev-card__avatar"
               onerror="this.src='/uploads/default.jpg'"
               alt="<?= safe_html($_rev['user_name']) ?>">
          <div>
            <div class="rev-card__name"><?= safe_html($_rev['user_name']) ?></div>
            <div class="rev-card__poi">
              <i class="fas fa-location-dot me-1"></i><?= safe_html($_rev['poi_name']) ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>
