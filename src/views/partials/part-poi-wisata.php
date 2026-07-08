<?php
require_once LIB_PATH . "v-poi-wisata.php";

function sanitizeHtml($html) {
  $html = preg_replace('/<\?(?:php|=)?[\s\S]*?\?>/i', '', $html);
  $html = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
  $html = preg_replace('/(<[^>]+?)\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '$1', $html);
  $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '', $html);
  return $html;
}
?>
<script src="<?= JS_URL ?>part-wisata.js" defer></script>
<div class="container">
  <section id="wisata-recommendations">
    <div class="mb-4">
      <span class="text-eyebrow" data-bhs="w.eyebrow">Recommendations</span>
      <h2 class="text-sub-hero mb-4" data-bhs="w.title">Wisata Favorit</h2>
    </div>
    <div class="poi-wisata-grid" id="poiwisataGrid">
      <?php if (!empty($wisata_poi)): ?>
      <?php foreach ($wisata_poi as $item): ?>
      <?php
      $img = htmlspecialchars($item['poi_image'] ?? IMG_URL . 'default.png');
      $name = htmlspecialchars($item['name'] ?? '');
      $desc = mb_substr(sanitizeHtml($item['description'] ?? ''), 0, 90);
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
            <span data-bhs="btn.detail">Lihat Detail</span>
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