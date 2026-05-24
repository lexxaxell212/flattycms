<?php
$_rev_stmt = $GLOBALS['pdo']->query("
    SELECT r.cerita, r.rating, r.judul,
           u.name AS user_name, u.avatar,
           p.name AS poi_name
    FROM poi_reviews r
    JOIN users u ON u.id = r.user_id
    JOIN poi   p ON p.id = r.poi_id
    ORDER BY r.rating DESC, r.created_at DESC
    LIMIT 6
");
$_rev_items = $_rev_stmt->fetchAll(PDO::FETCH_ASSOC);
?>