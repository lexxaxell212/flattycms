<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

// Hanya admin
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

verify_ajax_request('POST');
$pdo    = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

if (!verify_csrf_token($input['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

// ── POST — Tambah POI baru ───────────────────────────────────
if ($method === 'POST') {
    $name        = trim($input['name'] ?? '');
    $category_id = (int)($input['category_id'] ?? 0);
    $latitude    = (float)($input['latitude'] ?? 0);
    $longitude   = (float)($input['longitude'] ?? 0);
    $address     = trim($input['address'] ?? '');
    $description = trim($input['description'] ?? '');
    $is_active   = (int)($input['is_active'] ?? 1);

    if (!$name || !$category_id || !$latitude || !$longitude) {
        http_response_code(400);
        echo json_encode(['error' => 'Nama, kategori, latitude, longitude wajib diisi']);
        exit;
    }

    // Validasi kategori
    $stmt = $pdo->prepare("SELECT id FROM poi_categories WHERE id = ?");
    $stmt->execute([$category_id]);
    if (!$stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Kategori tidak valid']);
        exit;
    }

    // Generate slug unik
    $base_slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($name)));
    $slug      = $base_slug;
    $counter   = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT id FROM poi WHERE slug = ?");
        $stmt->execute([$slug]);
        if (!$stmt->fetch()) break;
        $slug = $base_slug . '-' . $counter++;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO poi (category_id, name, slug, description, address, latitude, longitude, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $category_id, $name, $slug,
            $description ?: null,
            $address ?: null,
            $latitude, $longitude, $is_active
        ]);

        echo json_encode([
            'success' => true,
            'poi_id'  => $pdo->lastInsertId(),
            'message' => 'Lokasi berhasil ditambahkan'
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

// ── DELETE — Hapus POI ───────────────────────────────────────
if ($method === 'DELETE') {
    $poi_id = (int)($input['poi_id'] ?? 0);

    if (!$poi_id) {
        http_response_code(400);
        echo json_encode(['error' => 'poi_id wajib']);
        exit;
    }

    try {
        // Cek apakah POI dipakai di trip_items
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM trip_items WHERE poi_id = ?");
        $stmt->execute([$poi_id]);
        if ((int)$stmt->fetchColumn() > 0) {
            http_response_code(409);
            echo json_encode(['error' => 'Lokasi ini sudah digunakan di trip planner, tidak bisa dihapus']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM poi WHERE id = ?");
        $stmt->execute([$poi_id]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'POI tidak ditemukan']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Lokasi berhasil dihapus']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);