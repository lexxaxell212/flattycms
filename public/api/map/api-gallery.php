<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];
$user_id = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : null;

// ── GET — List foto publik ───────────────────────────────────
if ($method === 'GET') {
  try {
    $where = [];
    $params = [];
    $limit = 12;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;

    if (!empty($_GET['poi_id'])) {
      $where[] = 'ph.poi_id = ?';
      $params[] = (int)$_GET['poi_id'];
    }

    $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM poi_photos ph {$whereSQL}");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    $stmt = $pdo->prepare("
            SELECT ph.id, ph.poi_id, ph.user_id, ph.photo_path, ph.caption, ph.created_at,
                   u.name AS uploader_name, u.avatar AS uploader_avatar,
                   p.name AS poi_name, p.slug AS poi_slug
            FROM poi_photos ph
            JOIN users u ON u.id = ph.user_id
            JOIN poi p ON p.id = ph.poi_id
            {$whereSQL}
            ORDER BY ph.created_at DESC
            LIMIT {$limit} OFFSET {$offset}
        ");
    $stmt->execute($params);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
      'success' => true,
      'data' => $photos,
      'total' => $total,
      'page' => $page,
      'pages' => ceil($total / $limit),
    ]);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

function get_gallery_rand($limit = 6) {
  $pdo = $GLOBALS['pdo'];
  $limit = (int)$limit;
  return $pdo->query("
    SELECT ph.photo_path, u.name AS uploader_name
    FROM poi_photos ph
    JOIN users u ON u.id = ph.user_id
    JOIN poi p ON p.id = ph.poi_id
    WHERE p.is_active = 1
    ORDER BY RAND()
    LIMIT {$limit}
  ")->fetchAll(PDO::FETCH_ASSOC);
}

// ── POST — semua write action ────────────────────────────────
if ($method !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  exit;
}

if (!$user_id) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Login diperlukan']);
  exit;
}

verify_ajax_request('POST');
validate_csrf();

$action = $_POST['action'] ?? 'upload';

// ── ACTION: upload ───────────────────────────────────────────
if ($action === 'upload') {
  $poi_id = (int)($_POST['poi_id'] ?? 0);
  $caption = trim($_POST['caption'] ?? '');

  if (!$poi_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'poi_id wajib diisi']);
    exit;
  }

  $stmt = $pdo->prepare("SELECT id FROM poi WHERE id = ? AND is_active = 1");
  $stmt->execute([$poi_id]);
  if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'POI tidak ditemukan']);
    exit;
  }

  if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File tidak valid']);
    exit;
  }

  $file = $_FILES['photo'];

  if ($file['size'] > 10 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File terlalu besar! Maksimal 10MB.']);
    exit;
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);

  $allowed_mime = ['image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp'];

  if (!array_key_exists($mime, $allowed_mime)) {
    echo json_encode(['success' => false, 'message' => 'Hanya JPG, PNG, dan WebP yang diizinkan.']);
    exit;
  }

  if (getimagesize($file['tmp_name']) === false) {
    echo json_encode(['success' => false, 'message' => 'File bukan gambar valid.']);
    exit;
  }

  $filename = 'gallery_' . uniqid('', true) . '.' . $allowed_mime[$mime];
  $dest = BASE_UPLOAD_PATH . $filename;

  if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file.']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("INSERT INTO poi_photos (poi_id, user_id, photo_path, caption) VALUES (?, ?, ?, ?)");
    $stmt->execute([$poi_id, $user_id, $filename, $caption ?: null]);

    echo json_encode([
      'success' => true,
      'photo_id' => $pdo->lastInsertId(),
      'url' => BASE_UPLOAD_URL . $filename,
      'message' => 'Foto berhasil diupload!',
    ]);

  } catch (PDOException $e) {
    @unlink($dest);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data foto']);
  }
  exit;
}

// ── ACTION: delete ───────────────────────────────────────────
if ($action === 'delete') {
  $photo_id = (int)($_POST['photo_id'] ?? 0);

  if (!$photo_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'photo_id wajib']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("SELECT photo_path FROM poi_photos WHERE id = ? AND user_id = ?");
    $stmt->execute([$photo_id, $user_id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$photo) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'Foto tidak ditemukan']);
      exit;
    }

    $pdo->prepare("DELETE FROM poi_photos WHERE id = ? AND user_id = ?")->execute([$photo_id, $user_id]);

    $filepath = BASE_UPLOAD_PATH . $photo['photo_path'];
    if (file_exists($filepath)) @unlink($filepath);

    echo json_encode(['success' => true, 'message' => 'Foto dihapus']);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Action tidak valid']);