<?php
require_once LIB_PATH . 'poi-actions.php';
$poi = get_poi_by_slug($slug);

if (!$poi) {
    http_response_code(404);
    require_once PUBLIC_PATH . 'errors/404.php';
    exit;
}
$page_title = $poi['name'] . ' - ' . SITE_NAME;
?>
<img src="<?= $poi['poi_image'] ?>" alt="<?= htmlspecialchars($poi['name']) ?>" class="img-fluid w-100 mb-4">
      <h1><?= htmlspecialchars($poi['name']) ?></h1>
      <p><?= nl2br(htmlspecialchars($poi['description'])) ?></p>