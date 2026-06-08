<?php
require_once LIB_PATH . 'poi-actions.php';
$poi = get_poi_by_slug($slug);

$page_title = $poi['name'] . ' — ' . SITE_NAME;
?>
<img src="<?= htmlspecialchars($poi['poi_image'] ?? '') ?>" 
     alt="<?= htmlspecialchars($poi['name']) ?>" 
     class="img-fluid w-100 mb-4">
<h1><?= htmlspecialchars($poi['name']) ?></h1>
<p><?= nl2br(htmlspecialchars($poi['description'] ?? '')) ?></p>