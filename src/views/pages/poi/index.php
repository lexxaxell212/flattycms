<?php
require_once LIB_PATH . 'poi-actions.php';
$poi = get_poi_by_slug($slug);

$page_title = $poi['name'] . ' - ' . SITE_NAME;
?>
<main class="main-content">
  <div class="container">
    <div class="page-header">
      <img src="<?= htmlspecialchars($poi['poi_image'] ?? '') ?>" alt="<?= htmlspecialchars($poi['name']) ?>" class="w-100 d-block mb-4 rounded-lg mx-auto" style="max-width:600px">
      <h1><?= htmlspecialchars($poi['name']) ?></h1>
    </div>
    <section>
      <p>
        <?= nl2br(htmlspecialchars($poi['description'] ?? '')) ?>
      </p>
    </section>
  </div>
</main>