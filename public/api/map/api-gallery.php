<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo    = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];

// ── GET — List foto (publik, bisa filter by poi_id) ──────────
// GET /api/map/api-gallery.php             → semua foto
// GET /api/map/api-gallery.php?poi_id=1   → foto per POI
// GET /api/map/api-gallery.php?page=2     → pagination (12 per page)
if ($method === 'GET') {
    try {
        $where  = [];
        $params = [];
        $limit  = 12;
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;

        if (!empty($_GET['poi_id'])) {
            $where[]  = 'ph.poi_id = ?';
            $params[] = (int)$_GET['poi_id'];
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Total count
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM poi_photos ph {$whereSQL}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Data
        $stmt = $pdo->prepare("
            SELECT ph.id, ph.poi_id, ph.photo_path, ph.caption, ph.created_at,
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
            'data'    => $photos,
            'total'   => $total,
            'page'    => $page,
            'pages'   => ceil($total / $limit),
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

// ── POST — Upload foto (login required) ──────────────────────
if ($method === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Login diperlukan untuk upload foto']);
        exit;
    }

    verify_ajax_request('POST');

    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }

    $poi_id  = (int)($_POST['poi_id'] ?? 0);
    $caption = trim($_POST['caption'] ?? '');
    $user_id = (int)$_SESSION['user_id'];

    if (!$poi_id) {
        http_response_code(400);
        echo json_encode(['error' => 'poi_id wajib diisi']);
        exit;
    }

    // Validasi POI exists & active
    $stmt = $pdo->prepare("SELECT id FROM poi WHERE id = ? AND is_active = 1");
    $stmt->execute([$poi_id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'POI tidak ditemukan']);
        exit;
    }

    // Validasi file
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'File tidak valid']);
        exit;
    }

    $file = $_FILES['photo'];

    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['error' => 'File terlalu besar! Maksimal 5MB.']);
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mime = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    if (!array_key_exists($mime, $allowed_mime)) {
        echo json_encode(['error' => 'Hanya JPG, PNG, dan WebP yang diizinkan.']);
        exit;
    }

    if (getimagesize($file['tmp_name']) === false) {
        echo json_encode(['error' => 'File bukan gambar valid.']);
        exit;
    }

    // Simpan file
    $ext      = $allowed_mime[$mime];
    $filename = 'gallery_' . uniqid('', true) . '.' . $ext;
    $dest     = BASE_UPLOAD_PATH . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        echo json_encode(['error' => 'Gagal menyimpan file.']);
        exit;
    }

    // Insert ke DB
    try {
        $stmt = $pdo->prepare("
            INSERT INTO poi_photos (poi_id, user_id, photo_path, caption)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$poi_id, $user_id, $filename, $caption ?: null]);
        $photo_id = $pdo->lastInsertId();

        echo json_encode([
            'success'  => true,
            'photo_id' => $photo_id,
            'url'      => BASE_UPLOAD_URL . $filename,
            'message'  => 'Foto berhasil diupload!',
        ]);

    } catch (PDOException $e) {
        // Kalau DB gagal, hapus file yang sudah terlanjur diupload
        @unlink($dest);
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan data foto']);
    }
    exit;
}

// ── DELETE — Hapus foto milik sendiri ────────────────────────
if ($method === 'DELETE') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Login diperlukan']);
        exit;
    }

    verify_ajax_request('POST');
    $input   = json_decode(file_get_contents('php://input'), true) ?? [];
    $user_id = (int)$_SESSION['user_id'];

    if (!verify_csrf_token($input['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }

    $photo_id = (int)($input['photo_id'] ?? 0);
    if (!$photo_id) {
        http_response_code(400);
        echo json_encode(['error' => 'photo_id wajib']);
        exit;
    }

    try {
        // Ambil path dulu sebelum delete
        $stmt = $pdo->prepare("SELECT photo_path FROM poi_photos WHERE id = ? AND user_id = ?");
        $stmt->execute([$photo_id, $user_id]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$photo) {
            http_response_code(404);
            echo json_encode(['error' => 'Foto tidak ditemukan']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM poi_photos WHERE id = ? AND user_id = ?");
        $stmt->execute([$photo_id, $user_id]);

        // Hapus file fisik
        $filepath = BASE_UPLOAD_PATH . $photo['photo_path'];
        if (file_exists($filepath)) @unlink($filepath);

        echo json_encode(['success' => true, 'message' => 'Foto dihapus']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);