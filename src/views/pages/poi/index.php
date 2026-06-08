<?php
require_once LIB_PATH . 'poi-actions.php';
//require_once LIB_PATH . 'v-reactions-page.php';
var_dump($slug);
$poi = get_poi_by_slug($slug);
var_dump($poi);
$page_title = $poi['name'] . ' - ' . SITE_NAME;

if (!$poi) {
    http_response_code(404);
    require_once PUBLIC_PATH . 'errors/404.php';
    exit;
}

?>
<!--
<script src="<?= JS_URL ?>reactions.js" defer></script> -->
<main class="main-content">
  <div class="container">
    <section>
      <img src="<?= $poi['poi_image'] ?>" alt="<?= htmlspecialchars($poi['name']) ?>" class="img-fluid w-100 mb-4">
      <h1><?= htmlspecialchars($poi['name']) ?></h1>
      <p><?= nl2br(htmlspecialchars($poi['description'])) ?></p>
      
      <hr class="my-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="justify-content-center">
          <div class="mb-2 text-center">
            <span class="text-muted">Beri like</span>
          </div>
          <div class="align-items-center">
            <button id="btn-reaction"
              class="btn <?php echo $_user_liked ? 'btn-primary btn-sm' : 'btn-outline-primary btn-sm'; ?>" data-id="<?php echo $_page_id; ?>" data-type="page">
              <i class="fas fa-heart me-1"></i>
              <span id="reaction-count"><?php echo $_reaction_count; ?></span>
            </button>
          </div>
        </div>
        <div class="justify-content-center">
          <div class="mb-2 text-center">
            <span class="text-muted">Bagikan artikel ini</span>
          </div>
          <div class="gap-2 align-items-center">
            <?php
            $_share_url   = urlencode(BASE_URL . 'pages/{$slug}/');
            $_share_title = urlencode('{$slug}');
            ?>
            <a href="https://wa.me/?text=<?php echo $_share_title; ?>%20<?php echo $_share_url; ?>"
               target="_blank" rel="noopener"
               class="btn btn-outline-primary btn-sm">
              <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_share_url; ?>"
               target="_blank" rel="noopener"
               class="btn btn-outline-primary btn-sm">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <button onclick="copyLink()"
               class="btn btn-outline-primary btn-sm">
              <i class="fa-brands fa-instagram"></i>
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>