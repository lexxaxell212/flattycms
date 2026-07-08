<?php
require_once LIB_PATH . "v-poi-hotel.php";
?>
<div class="container">
  <section id="hotel-recommendations">
    <span class="text-eyebrow" data-bhs="h.eyebrow">
      Recommendations
    </span>
    <h2 class="text-sub-hero mb-4" data-bhs="h.title">
      Hotel Favorit
    </h2>
    <div class="poi-hotel-grid" id="poiHotelGrid">
      <?php if (!empty($hotel_poi)): ?>
      <?php foreach ($hotel_poi as $item): ?>
      <?php
      $img = htmlspecialchars($item['poi_image'] ?? IMG_URL . 'default.png');
      $name = htmlspecialchars($item['name'] ?? '');
      $desc = mb_substr(sanitizeHtml($item['description'] ?? '', 0, 90));
      $desc .= mb_strlen($item['description'] ?? '') > 90 ? '...' : '';
      $url = htmlspecialchars($item['slug'] ?? '');
      ?>
      <div class="poi-hotel-card"
        style="background-image: url('<?= $img ?>')">
        <div class="poi-hotel-overlay">
          <h3 class="poi-hotel-name"><?= $name ?></h3>
          <div class="poi-hotel-desc">
            <?= $desc ?>
          </div>
          <?php if (!empty($url)): ?>
          <a href="/poi/<?= $url ?>"
            class="poi-wisata-btn"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Lihat detail <?= $name ?>">
            <span data-bhs="btn.detail">Lihat Detail</span>
            <i class="arrow-icon fas fa-angle-right ms-2"></i>
          </a>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <?php else : ?>
      <p class="poi-hotel-empty">
        Belum ada data tersedia.
      </p>
      <?php endif; ?>
    </div>
  </section>
</div>