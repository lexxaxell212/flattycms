<?php
require_once LIB_PATH . "v-poi-wisata.php";
?>
<script src="<?= JS_URL ?>part-wisata.js" defer></script>
<div class="container">
  <section id="wisata-recommendations">
    <div class="mb-4">
      <span class="text-eyebrow">Recommendations</span>
      <h2 class="text-sub-hero mb-4">Wisata Favorit</h2>
    </div>
    <div class="poi-wisata-grid" id="poiwisataGrid">
      <?php if (!empty($wisata_poi)): ?>
      <?php foreach ($wisata_poi as $item): ?>
      <?php
      $img = htmlspecialchars($item['poi_image'] ?? BASE_UPLOAD_URL . 'default.jpg');
      $name = htmlspecialchars($item['name'] ?? '');
      $desc = htmlspecialchars(mb_substr($item['description'] ?? '', 0, 90));
      $desc .= mb_strlen($item['description'] ?? '') > 90 ? '...' : '';
      $url = htmlspecialchars($item['slug'] ?? '');
      ?>
      <div class="poi-wisata-card" style="--card-bg: url('<?= $img ?>')">
        <div class="poi-wisata-overlay">
          <h3 class="poi-wisata-name"><?= $name ?></h3>
          <p class="poi-wisata-desc">
            <?= $desc ?>
          </p>
          <?php if (!empty($url)): ?>
          <a href="/poi/<?= $url ?>"
            class="poi-wisata-btn"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Lihat detail <?= $name ?>">
            Lihat Detail
            <i class="arrow-icon fas fa-angle-right ms-2"></i>
          </a>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <?php else : ?>
      <p class="poi-wisata-empty">
        Belum ada data tersedia.
      </p>
      <?php endif; ?>
    </div>
    <div class="poi-wisata-dots" id="poiWisataDots"></div>
  </section>
</div>