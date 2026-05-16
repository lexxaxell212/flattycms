<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo    = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];

// Semua method selain GET butuh login
if ($method !== 'GET' && !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Login diperlukan']);
    exit;
}

// ── GET ──────────────────────────────────────────────────────
// GET /api/map/api-trips.php          → list trip milik user login
// GET /api/map/api-trips.php?id=1     → detail trip + items + notes
if ($method === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'data' => [], 'logged_in' => false]);
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];

    try {
        if (isset($_GET['id'])) {
            // Detail trip lengkap
            $stmt = $pdo->prepare("
                SELECT t.*,
                       ti.id AS item_id, ti.poi_id, ti.order_index, ti.distance_from_prev,
                       p.name AS poi_name, p.latitude, p.longitude, p.address,
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
                echo json_encode(['error' => 'Trip tidak ditemukan']);
                exit;
            }

            // Susun struktur trip + items
            $trip = [
                'id'               => $rows[0]['id'],
                'title'            => $rows[0]['title'],
                'start_point_name' => $rows[0]['start_point_name'],
                'start_lat'        => $rows[0]['start_lat'],
                'start_lng'        => $rows[0]['start_lng'],
                'total_distance'   => $rows[0]['total_distance'],
                'created_at'       => $rows[0]['created_at'],
                'items'            => [],
            ];

            foreach ($rows as $row) {
                if (!$row['item_id']) continue;
                $trip['items'][] = [
                    'id'                 => $row['item_id'],
                    'poi_id'             => $row['poi_id'],
                    'poi_name'           => $row['poi_name'],
                    'latitude'           => $row['latitude'],
                    'longitude'          => $row['longitude'],
                    'address'            => $row['address'],
                    'order_index'        => $row['order_index'],
                    'distance_from_prev' => $row['distance_from_prev'],
                    'note_id'            => $row['note_id'],
                    'note'               => $row['note'],
                ];
            }

            echo json_encode(['success' => true, 'data' => $trip]);
            exit;
        }

        // List semua trip milik user
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
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

// Semua method write butuh AJAX + CSRF
verify_ajax_request($method === 'POST' ? 'POST' : 'POST');
$input   = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$user_id = (int)$_SESSION['user_id'];

if (!verify_csrf_token($input['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

// ── POST — Simpan trip baru ───────────────────────────────────
if ($method === 'POST') {
    $title      = trim($input['title'] ?? 'Trip Bandungku');
    $start_name = trim($input['start_point_name'] ?? '');
    $start_lat  = (float)($input['start_lat'] ?? 0);
    $start_lng  = (float)($input['start_lng'] ?? 0);
    $items      = $input['items'] ?? []; // [{poi_id, order_index, distance_from_prev}]

    if (!$start_name || !$start_lat || !$start_lng) {
        http_response_code(400);
        echo json_encode(['error' => 'Titik awal wajib diisi']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Hitung total jarak
        $total_dist = 0;
        foreach ($items as $item) {
            $total_dist += (float)($item['distance_from_prev'] ?? 0);
        }

        $stmt = $pdo->prepare("
            INSERT INTO trips (user_id, title, start_point_name, start_lat, start_lng, total_distance)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $title, $start_name, $start_lat, $start_lng, $total_dist ?: null]);
        $trip_id = $pdo->lastInsertId();

        // Insert items
        if (!empty($items)) {
            $stmt = $pdo->prepare("
                INSERT INTO trip_items (trip_id, poi_id, order_index, distance_from_prev)
                VALUES (?, ?, ?, ?)
            ");
            foreach ($items as $idx => $item) {
                $stmt->execute([
                    $trip_id,
                    (int)$item['poi_id'],
                    (int)($item['order_index'] ?? $idx + 1),
                    (float)($item['distance_from_prev'] ?? 0) ?: null,
                ]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'trip_id' => $trip_id, 'message' => 'Trip berhasil disimpan!']);

    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan trip']);
    }
    exit;
}

// ── PUT — Update judul / total jarak ─────────────────────────
if ($method === 'PUT') {
    $trip_id    = (int)($input['trip_id'] ?? 0);
    $title      = trim($input['title'] ?? '');
    $total_dist = isset($input['total_distance']) ? (float)$input['total_distance'] : null;

    if (!$trip_id) {
        http_response_code(400);
        echo json_encode(['error' => 'trip_id wajib']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE trips SET title = ?, total_distance = ?, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$title, $total_dist, $trip_id, $user_id]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Trip tidak ditemukan']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Trip diperbarui']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

// ── DELETE ────────────────────────────────────────────────────
if ($method === 'DELETE') {
    $trip_id = (int)($input['trip_id'] ?? 0);

    if (!$trip_id) {
        http_response_code(400);
        echo json_encode(['error' => 'trip_id wajib']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM trips WHERE id = ? AND user_id = ?");
        $stmt->execute([$trip_id, $user_id]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Trip tidak ditemukan']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Trip dihapus']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);