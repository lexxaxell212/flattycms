<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('GET');

if (!isset($GLOBALS['pdo'])) {
 http_response_code(500);
 echo json_encode(['error' => 'Koneksi database gagal.']);
 exit;
}

$pdo = $GLOBALS['pdo'];

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$results = [];

$stmt = $pdo->query("
    SELECT id, title, `desc` AS description, button_link, 'cmpt' AS source, created_at
    FROM cmpt
    WHERE status = 'active'
    AND category IN ('bandung_pusat','bandung_utara','bandung_selatan','bandung_timur','bandung_barat')
    ORDER BY RAND()
    LIMIT 3
  ");
$results = array_merge($results, $stmt->fetchAll());

$stmt = $pdo->query("
    SELECT id, title, excerpt AS description, slug, 'allcontent_posts' AS source, created_at
    FROM allcontent_posts
    WHERE status = 'active'
    ORDER BY RAND()
    LIMIT 3
  ");
$results = array_merge($results, $stmt->fetchAll());

$stmt = $pdo->query("
    SELECT id, name AS title, description AS description, slug, 'poi' AS source, created_at
    FROM poi
    WHERE is_active = 1
    ORDER BY RAND()
    LIMIT 3
  ");
$results = array_merge($results, $stmt->fetchAll());

shuffle($results);

$results = array_slice($results, 0, 6);

echo json_encode([
 'total' => count($results),
 'results' => $results,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);