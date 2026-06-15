<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

// GET /api/map/api-poi.php?id=1        → detail 1 POI
// GET /api/map/api-poi.php             → semua POI aktif
// GET /api/map/api-poi.php?category=1  → filter by category
// GET /api/map/api-poi.php?q=kawah     → search nama

try {
  if (isset($_GET['id'])) {
    // Detail 1 POI
    $stmt = $pdo->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon,
                   COUNT(ph.id) AS photo_count
            FROM poi p
            JOIN poi_categories c ON c.id = p.category_id
            LEFT JOIN poi_photos ph ON ph.poi_id = p.id
            WHERE p.id = ? AND p.is_active = 1
            GROUP BY p.id
        ");
    $stmt->execute([(int)$_GET['id']]);
    $poi = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$poi) {
      http_response_code(404);
      echo json_encode(['error' => 'POI tidak ditemukan']);
      exit;
    }

    echo json_encode(['success' => true, 'data' => $poi]);
    exit;
  }

  // List POI
  $where = ['p.is_active = 1'];
  $params = [];

  if (!empty($_GET['category'])) {
    $where[] = 'p.category_id = ?';
    $params[] = (int)$_GET['category'];
  }

  if (!empty($_GET['q'])) {
    $where[] = 'p.name LIKE ?';
    $params[] = '%' . $_GET['q'] . '%';
  }

  $whereSQL = implode(' AND ', $where);

  $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.slug, p.description, p.address,
               p.latitude, p.longitude, p.poi_image,
               c.id AS category_id, c.name AS category_name,
               c.slug AS category_slug, c.icon AS category_icon
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        WHERE {$whereSQL}
        ORDER BY p.name ASC
    ");
  $stmt->execute($params);
  $pois = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Ambil semua kategori sekalian buat filter UI
  $categories = $pdo->query("SELECT * FROM poi_categories ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'success' => true,
    'data' => $pois,
    'categories' => $categories,
    'total' => count($pois),
  ]);

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error']);
}