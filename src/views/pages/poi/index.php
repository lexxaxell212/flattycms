<?php
require_once LIB_PATH . 'poi-actions.php';
$poi = get_poi_by_slug($slug);
$poi_id = $poi['id'];

$page_title = $poi['name'] . ' - ' . SITE_NAME;

function sanitizeHtml($html) {
  $html = preg_replace('/<\?(?:php|=)?[\s\S]*?\?>/i', '', $html);
  $html = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
  $html = preg_replace('/(<[^>]+?)\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '$1', $html);
  $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '', $html);
  return $html;
}
?>
<script src="<?= JS_URL ?>reactions.js" defer></script>
<main class="main-content">
  <div class="container">
    <div class="page-header">
      <img src="<?= htmlspecialchars($poi['poi_image'] ?? '') ?>" alt="<?= htmlspecialchars($poi['name']) ?>" class="w-100 d-block rounded-lg mx-auto mb-2" style="max-width:600px" onerror="this.onerror=null;this.src='/assets/images/default.png'">
      <div class="text-center">
        <?= sanitizeHtml($poi['copyright'] ?? '') ?>
      </div>
    </div>
    <section class="revealed">
      <h1 class="mb-4">
        <em class="styled">
        <?= htmlspecialchars($poi['name']) ?>
        </em>
      </h1>
      <div>
        <?= sanitizeHtml($poi['description'] ?? '') ?>
      </div>
    </section>
    <hr class="my-4">
    <?php
    require_once LIB_PATH . "v-reactions-poi.php";
    ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="justify-content-center">
        <div class="mb-2 text-center">
          <span class="text-muted">Beri like</span>
        </div>
        <div class="align-items-center">
          <button
            id="btn-reaction"
            class="btn <?= $__user_liked ? 'btn-primary btn-fit' : 'btn-outline-primary btn-fit' ?>" data-id="<?= $poi_id ?>" data-type="place">
            <i class="fas fa-heart me-1"></i>
            <span id="reaction-count"><?= $__reaction_count ?></span>
          </button>
        </div>
      </div>
      <div class="justify-content-center">
        <div class="mb-2 text-center">
          <span class="text-muted">Bagikan artikel ini</span>
        </div>
        <div class="gap-2 align-items-center">
          <?php $share_url = urlencode(BASE_URL . 'poi/' . $slug); ?>
          <?php $share_title = urlencode(htmlspecialchars($poi['name'])); ?>
          <a href="https://wa.me/?text=<?= $share_title ?>%20<?= $share_url ?>"
            target="_blank" rel="noopener"
            class="btn btn-outline-primary btn-fit">
            <i class="fa-brands fa-whatsapp"></i>
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>"
            target="_blank" rel="noopener"
            class="btn btn-outline-primary btn-fit">
            <i class="fa-brands fa-facebook"></i>
          </a>
          <button onclick="copyLink()"
            class="btn btn-outline-primary btn-fit">
            <i class="fa-brands fa-instagram"></i>
          </button>
        </div>
      </div>
    </div>

  <hr class="my-4">
<section class="revealed">
  <h2 class="h3 mb-3">Galeri Foto</h2>
  <div id="poiGalleryGrid" class="row g-3">
    <div class="skeleton-wrapper">
      <div></div>
    </div>
  </div>
</section>

<hr class="my-4">
<section class="revealed">
  <h2 class="h3 mb-3">Review</h2>
  <div id="poiReviewGrid" class="gal-review-grid">
    <div class="skeleton-wrapper">
      <div></div>
    </div>
  </div>
</section>

<script>
  const POI_ID_CURRENT = <?= (int)$poi_id ?>;
  const BASE = CONFIG.baseUrl;
</script>
<script src="<?= JS_URL ?>poi-detail.js" defer></script>
    
  </div>
</main>