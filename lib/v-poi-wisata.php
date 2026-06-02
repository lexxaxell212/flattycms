<?php
function get_wisata_poi($limit = 6) {
    $pdo = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.slug, p.description, p.poi_image, p.poi_url,
               c.id AS category_id, c.name AS category_name, c.slug AS category_slug
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        WHERE c.slug = ? AND p.is_active = 1
        ORDER BY RAND()
        LIMIT ?
    ");
    $stmt->execute(['wisata', (int)$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$GLOBALS['wisata_poi'] = get_wisata_poi(6);