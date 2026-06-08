<?php
var_dump($slug);
require_once LIB_PATH . 'poi-actions.php';
$poi = get_poi_by_slug($slug);
var_dump($poi);
?>
<img src="<?= $poi['poi_image'] ?>" alt="<?= htmlspecialchars($poi['name']) ?>" class="img-fluid w-100 mb-4">
      <h1><?= htmlspecialchars($poi['name']) ?></h1>
      <p><?= nl2br(htmlspecialchars($poi['description'])) ?></p>