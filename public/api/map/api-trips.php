<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];
$user_id = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : null;

// ── GET ──────────────────────────────────────────────────────
if ($method === 'GET') {
  if (!$user_id) {
    echo json_encode(['success' => true, 'data' => [], 'logged_in' => false]);
    exit;
  }

  try {
    if (isset($_GET['id'])) {
      $stmt = $pdo->prepare("
                SELECT t.id, t.title, t.start_point_name, t.start_lat, t.start_lng,
                       t.total_distance, t.duration, t.route_polyline, t.created_at,
                       ti.id AS item_id, ti.poi_id, ti.order_index, ti.distance_from_prev,
                       p.name AS poi_name, p.latitude, p.longitude, p.address, p.poi_image, p.description,
                       tn.id AS note_id, tn.note
                FROM trips t
                LEFT JOIN trip_items ti ON ti.trip_id = t.id
                LEFT JOIN poi p ON p.id = ti.poi_id
                LEFT JOIN trip_notes tn ON tn.trip_item_id = ti.id AND tn.user_id = ?
                WHERE t.id = ? AND t.user_id = ?
                ORDER BY ti.order_index ASC
            ");
      $stmt->execute([$user_id, (int)$_GET['id'], $user_id]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (empty($rows)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Trip tidak ditemukan']);
        exit;
      }

      $trip = [
        'id' => $rows[0]['id'],
        'title' => $rows[0]['title'],
        'start_point_name' => $rows[0]['start_point_name'],
        'start_lat' => $rows[0]['start_lat'],
        'start_lng' => $rows[0]['start_lng'],
        'total_distance' => $rows[0]['total_distance'],
        'duration' => $rows[0]['duration'],
        'route_polyline' => $rows[0]['route_polyline'],
        'created_at' => $rows[0]['created_at'],
        'items' => [],
      ];
      foreach ($rows as $row) {
        if (!$row['item_id']) continue;
        $trip['items'][] = [
          'id' => $row['item_id'],
          'poi_id' => $row['poi_id'],
          'poi_name' => $row['poi_name'],
          'latitude' => $row['latitude'],
          'longitude' => $row['longitude'],
          'address' => $row['address'],
          'poi_image' => $row['poi_image'],
          'description' => $row['description'],
          'order_index' => $row['order_index'],
          'distance_from_prev' => $row['distance_from_prev'],
          'note_id' => $row['note_id'],
          'note' => $row['note'],
        ];
      }

      echo json_encode(['success' => true, 'data' => $trip]);
      exit;
    }

    $stmt = $pdo->prepare("
            SELECT t.*, COUNT(ti.id) AS total_stops
            FROM trips t
            LEFT JOIN trip_items ti ON ti.trip_id = t.id
            WHERE t.user_id = ?
            GROUP BY t.id
            ORDER BY t.updated_at DESC
        ");
    $stmt->execute([$user_id]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $trips, 'logged_in' => true]);

  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

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

$action = $_POST['action'] ?? '';

if ($action === 'save') {
  $title = trim($_POST['title'] ?? 'Isi Nama Trip...');
  $start_name = trim($_POST['start_point_name'] ?? '');
  $start_lat = (float)($_POST['start_lat'] ?? 0);
  $start_lng = (float)($_POST['start_lng'] ?? 0);
  $route_polyline = $_POST['route_polyline'] ?? null;
  $duration = isset($_POST['duration']) ? (float)$_POST['duration'] : null;
  $total_distance_input = isset($_POST['total_distance']) ? (float)$_POST['total_distance'] : null;
  $items = json_decode($_POST['items'] ?? '[]', true) ?: [];

  if (!$start_name || !$start_lat || !$start_lng) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Titik awal wajib diisi']);
    exit;
  }

  try {
    $pdo->beginTransaction();

    $total_dist = $total_distance_input;

    $stmt = $pdo->prepare("
            INSERT INTO trips (user_id, title, start_point_name, start_lat,
            start_lng, total_distance, route_polyline, duration)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
    $stmt->execute([$user_id, $title, $start_name, $start_lat, $start_lng,
      $total_dist ?: null, $route_polyline ?: null, $duration]);
    $trip_id = $pdo->lastInsertId();

    if (!empty($items)) {
      $stmtItem = $pdo->prepare("
                INSERT INTO trip_items (trip_id, poi_id, order_index, distance_from_prev)
                VALUES (?, ?, ?, ?)
            ");
      $stmtNote = $pdo->prepare("
                INSERT INTO trip_notes (trip_item_id, user_id, note) VALUES (?, ?, ?)
            ");
      foreach ($items as $idx => $item) {
        $stmtItem->execute([
          $trip_id,
          (int)$item['poi_id'],
          (int)($item['order_index'] ?? $idx + 1),
          (float)($item['distance_from_prev'] ?? 0) ?: null,
        ]);
        if (!empty($item['note'])) {
          $stmtNote->execute([$pdo->lastInsertId(), $user_id, $item['note']]);
        }
      }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'trip_id' => $trip_id, 'message' => 'Trip berhasil disimpan!']);

  } catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan trip']);
  }
  exit;
}

if ($action === 'update') {
  $trip_id = (int)($_POST['trip_id'] ?? 0);
  $title = trim($_POST['title'] ?? '');
  $total_dist = isset($_POST['total_distance']) ? (float)$_POST['total_distance'] : null;

  if (!$trip_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'trip_id wajib']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("UPDATE trips SET title = ?, total_distance = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $total_dist, $trip_id, $user_id]);
    echo json_encode(['success' => true, 'message' => 'Trip diperbarui']);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
  }
  exit;
}

if ($action === 'delete') {
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

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Action tidak valid']);