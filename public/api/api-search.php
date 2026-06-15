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

$q = trim($_GET['q'] ?? '');

if (mb_strlen($q) < 2) {
  echo json_encode(['results' => [], 'total' => 0]);
  exit;
}

$keyword = '%' . $q . '%';
$results = [];

$stmt = $pdo->prepare("
    SELECT
      id,
      title,
      `desc`       AS description,
      button_link,
      'cmpt'       AS source
    FROM cmpt
    WHERE status = 'active'
    AND category IN ('bandung_pusat','bandung_utara','bandung_selatan','bandung_timur','bandung_barat')
    AND (title LIKE :q OR `desc` LIKE :q2)
    ORDER BY title ASC
    LIMIT 10
  ");
$stmt->execute([':q' => $keyword, ':q2' => $keyword]);
$results = array_merge($results, $stmt->fetchAll());

$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        excerpt AS description,
        slug,
        'allcontent_posts' AS source
    FROM allcontent_posts
    WHERE title LIKE :q
       OR excerpt LIKE :q2
    ORDER BY title ASC
    LIMIT 10
  ");
$stmt->execute([':q' => $keyword, ':q2' => $keyword]);
$results = array_merge($results, $stmt->fetchAll());

$stmt = $pdo->prepare("
    SELECT
        id,
        name       AS title,
        description AS description,
        slug,
        'poi'      AS source
    FROM poi
    WHERE is_active = 1
    AND (name LIKE :q OR description LIKE :q2)
    ORDER BY name ASC
    LIMIT 10
  ");
$stmt->execute([':q' => $keyword, ':q2' => $keyword]);
$results = array_merge($results, $stmt->fetchAll());

usort($results, function ($a, $b) use ($q) {
  $aExact = stripos($a['title'], $q) === 0 ? 0 : 1;
  $bExact = stripos($b['title'], $q) === 0 ? 0 : 1;
  if ($aExact !== $bExact) return $aExact - $bExact;
  return strcmp($a['title'], $b['title']);
});

$results = array_slice($results, 0, 20);

echo json_encode([
  'query' => $q,
  'total' => count($results),
  'results' => $results,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);