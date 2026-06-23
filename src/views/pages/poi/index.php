<?php
require_once LIB_PATH . 'poi-actions.php';
$poi = get_poi_by_slug($slug);

$page_title = $poi['name'] . ' - ' . SITE_NAME;
?>
<main class="main-content">
  <div class="container">
    <div class="page-header">
      <img src="<?= htmlspecialchars($poi['poi_image'] ?? '') ?>" alt="<?= htmlspecialchars($poi['name']) ?>" class="w-100 d-block rounded-lg mx-auto" style="max-width:600px">
    </div>
    <section class="revealed">
      <h1 class="h2"><?= htmlspecialchars($poi['name']) ?></h1>
      <p>
        <?= nl2br(htmlspecialchars($poi['description'] ?? '')) ?>
      </p>
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
            class="btn <?= $user_liked ? 'btn-primary btn-fit' : 'btn-outline-primary btn-fit' ?>" data-id="<?= $slug ?>" data-type="blog">
            <i class="fas fa-heart me-1"></i>
            <span id="reaction-count"><?= $reaction_count ?></span>
          </button>
        </div>
      </div>
      <div class="justify-content-center">
        <div class="mb-2 text-center">
          <span class="text-muted">Bagikan artikel ini</span>
        </div>
        <div class="gap-2 align-items-center">
          <?php $share_url = urlencode(BASE_URL . 'poi/' . ($poi['name'] ?? '')); ?>
          <?php $share_title = urlencode($poi['name'] ?? ''); ?>
          <a href="https://wa.me/?text=<?= $share_title ?>%20<?= $share_url ?>"
            target="_blank" rel="noopener"
            class="btn btn-outline-primary btn-fit">
            <i class="fa-brands fa-whatsapp"></i>
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>"
            target="_blank" rel="noopener"
            class="btn btn-outline-primary btn-fit">
            <i class="fa-brands fa-facebo<ok-f"></i>
          </a>
          <button onclick="copyLink()"
            class="btn btn-outline-primary btn-fit">
            <i class="fa-brands fa-instagram"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>