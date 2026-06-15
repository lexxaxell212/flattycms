<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];
$user_id = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : null;

if (!$user_id) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Login diperlukan']);
  exit;
}

// ── GET — ambil semua data profil + kontribusi ───────────────
if ($method === 'GET') {
  try {
    // Info user
    $stmt = $pdo->prepare("SELECT id, name, display_name, email, avatar, created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Foto gallery
    $stmt = $pdo->prepare("
            SELECT ph.id, ph.photo_path, ph.caption, ph.created_at,
                   p.name AS poi_name, p.slug AS poi_slug
            FROM poi_photos ph
            JOIN poi p ON p.id = ph.poi_id
            WHERE ph.user_id = ?
            ORDER BY ph.created_at DESC
        ");
    $stmt->execute([$user_id]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trips
    $stmt = $pdo->prepare("
            SELECT t.id, t.title, t.start_point_name, t.total_distance, t.duration, t.created_at,
                   COUNT(ti.id) AS total_stops
            FROM trips t
            LEFT JOIN trip_items ti ON ti.trip_id = t.id
            WHERE t.user_id = ?
            GROUP BY t.id
            ORDER BY t.created_at DESC
        ");
    $stmt->execute([$user_id]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Reactions + judul konten
    $stmt = $pdo->prepare("
            SELECT r.id, r.content_type, r.content_id, r.created_at,
                   CASE
                     WHEN r.content_type = 'blog' THEN (SELECT title FROM allcontent_posts WHERE id = r.content_id)
                     WHEN r.content_type = 'page' THEN (SELECT title FROM pages WHERE id = r.content_id)
                     ELSE NULL
                   END AS content_title
            FROM reactions r
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
    $stmt->execute([$user_id]);
    $reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
      'success' => true,
      'user' => $user,
      'photos' => $photos,
      'trips' => $trips,
      'reactions' => $reactions,
    ]);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

// ── POST — semua write action ────────────────────────────────
if ($method !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  exit;
}

verify_ajax_request('POST');
validate_csrf();

$action = $_POST['action'] ?? '';

// ── ACTION: update_name ──────────────────────────────────────
if ($action === 'update_name') {
  $display_name = trim($_POST['display_name'] ?? '');

  if (!$display_name) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama tidak boleh kosong']);
    exit;
  }

  if (mb_strlen($display_name) > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama maksimal 100 karakter']);
    exit;
  }

  try {
    $pdo->prepare("UPDATE users SET display_name = ? WHERE id = ?")
    ->execute([$display_name, $user_id]);

    // Update session
    $_SESSION['user']['display_name'] = $display_name;

    echo json_encode(['success' => true, 'message' => 'Nama berhasil diperbarui!']);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

// ── ACTION: delete_photo ─────────────────────────────────────
if ($action === 'delete_photo') {
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

// ── ACTION: delete_trip ──────────────────────────────────────
if ($action === 'delete_trip') {
  $trip_id = (int)($_POST['trip_id'] ?? 0);

  if (!$trip_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'trip_id wajib']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("DELETE FROM trips WHERE id = ? AND user_id = ?");
    $stmt->execute([$trip_id, $user_id]);

    if ($stmt->rowCount() === 0) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'Trip tidak ditemukan']);
      exit;
    }

    echo json_encode(['success' => true, 'message' => 'Trip dihapus']);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

// ── ACTION: delete_reaction ──────────────────────────────────
if ($action === 'delete_reaction') {
  $reaction_id = (int)($_POST['reaction_id'] ?? 0);

  if (!$reaction_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'reaction_id wajib']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("DELETE FROM reactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$reaction_id, $user_id]);

    if ($stmt->rowCount() === 0) {
      http_response_code(404);
      echo json_encode(['success' => false, 'message' => 'Reaksi tidak ditemukan']);
      exit;
    }

    echo json_encode(['success' => true, 'message' => 'Reaksi dihapus']);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Action tidak valid']);