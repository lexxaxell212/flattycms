<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo     = $GLOBALS['pdo'];
$method  = $_SERVER['REQUEST_METHOD'];
$user_id = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : null;

// ── GET — List review publik ─────────────────────────────────
if ($method === 'GET') {
    verify_ajax_request('GET');

    try {
        $where  = [];
        $params = [];
        $limit  = 12;
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;

        if (!empty($_GET['poi_id'])) {
            $where[]  = 'r.poi_id = ?';
            $params[] = (int)$_GET['poi_id'];
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM poi_reviews r {$whereSQL}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT
                r.id, r.rating, r.judul, r.cerita, r.created_at,
                u.id   AS user_id,
                u.name AS user_name,
                u.avatar,
                p.id   AS poi_id,
                p.name AS poi_name
            FROM poi_reviews r
            JOIN users u ON u.id = r.user_id
            JOIN poi   p ON p.id = r.poi_id
            {$whereSQL}
            ORDER BY r.created_at DESC
            LIMIT {$limit} OFFSET {$offset}
        ");
        $stmt->execute($params);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data'    => $reviews,
            'total'   => $total,
            'page'    => $page,
            'pages'   => ceil($total / $limit),
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

// ── POST — Kirim review ──────────────────────────────────────
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

$poi_id = (int)($_POST['poi_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$judul  = trim($_POST['judul']   ?? '');
$cerita = trim($_POST['cerita']  ?? '');

// validasi
if (!$poi_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Lokasi tidak valid']);
    exit;
}

if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Rating harus antara 1–5']);
    exit;
}

if (strlen($cerita) < 10) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cerita terlalu singkat']);
    exit;
}

if (strlen($cerita) > 5000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cerita terlalu panjang']);
    exit;
}

if (strlen($judul) > 255) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Judul terlalu panjang']);
    exit;
}

try {
    // cek poi exists
    $stmt = $pdo->prepare("SELECT id FROM poi WHERE id = ? AND is_active = 1");
    $stmt->execute([$poi_id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Lokasi tidak ditemukan']);
        exit;
    }

    // 1 user = 1 review per poi
    $stmt = $pdo->prepare("SELECT id FROM poi_reviews WHERE poi_id = ? AND user_id = ?");
    $stmt->execute([$poi_id, $user_id]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Kamu sudah pernah mereview tempat ini']);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO poi_reviews (poi_id, user_id, rating, judul, cerita)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$poi_id, $user_id, $rating, $judul ?: null, $cerita]);

    echo json_encode([
        'success'   => true,
        'review_id' => $pdo->lastInsertId(),
        'message'   => 'Review berhasil dikirim!',
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}