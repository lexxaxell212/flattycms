<?php
$pdo = $GLOBALS['pdo'];
$stmt = $pdo->prepare("
     SELECT p.id, p.name, p.slug, p.description, p.poi_image, p.poi_url,
            c.id AS category_id, c.name AS category_name, c.slug AS category_slug
     FROM poi p
     JOIN poi_categories c ON c.id = p.category_id
     WHERE c.slug = ? AND p.is_active = 1
     ORDER BY RAND()
     LIMIT 6
 ");
 $stmt->execute(['wisata', 6]);
 $wisata_poi = $stmt->fetchAll(PDO::FETCH_ASSOC);