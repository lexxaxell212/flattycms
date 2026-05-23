<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

$method = $_SERVER['REQUEST_METHOD'];

// ── GET : ambil daftar review ────────────────────────────────────────────────
if ($method === 'GET') {
    verify_ajax_request('GET');
    $page    = max(1, (int)($_GET['page']    ?? 1));
    $poi_id  = isset($_GET['poi_id']) ? (int)$_GET['poi_id'] : 0;
    $limit   = 12;
    $offset  = ($page - 1) * $limit;

    $where  = $poi_id ? 'WHERE r.poi_id = ?' : '';
    $params = $poi_id ? [$poi_id] : [];

    // total
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM poi_reviews r $where");
    $stmtCount->execute($params);
    $total = (int)$stmtCount->fetchColumn();

    // data
    $sql = "
        SELECT
            r.id,
            r.rating,
            r.judul,
            r.cerita,
            r.created_at,
            u.id   AS user_id,
            u.name AS user_name,
            u.avatar,
            p.id   AS poi_id,
            p.name AS poi_name
        FROM poi_reviews r
        JOIN users u ON u.id = r.user_id
        JOIN poi   p ON p.id = r.poi_id
        $where
        ORDER BY r.created_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmtData = $pdo->prepare($sql);
    $stmtData->execute([...$params, $limit, $offset]);
    $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'total'   => $total,
        'page'    => $page,
        'pages'   => (int)ceil($total / $limit),
        'data'    => $rows,
    ]);
    exit;
}

// ── POST : kirim review ──────────────────────────────────────────────────────
if ($method === 'POST') {
    verify_ajax_request('POST');

    // harus login
    if (empty($_SESSION['user']['id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Login dulu ya!']);
        exit;
    }

    // CSRF
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Token tidak valid']);
        exit;
    }

    $user_id = (int)$_SESSION['user']['id'];
    $poi_id  = (int)($_POST['poi_id']  ?? 0);
    $rating  = (int)($_POST['rating']  ?? 0);
    $judul   = trim($_POST['judul']    ?? '');
    $cerita  = trim($_POST['cerita']   ?? '');

    // validasi
    if ($poi_id  < 1)        { echo json_encode(['success'=>false,'message'=>'Lokasi tidak valid']);           exit; }
    if ($rating  < 1 || $rating > 5) { echo json_encode(['success'=>false,'message'=>'Rating harus 1–5']);    exit; }
    if (strlen($cerita) < 10){ echo json_encode(['success'=>false,'message'=>'Cerita terlalu singkat']);       exit; }
    if (strlen($cerita) > 5000){ echo json_encode(['success'=>false,'message'=>'Cerita terlalu panjang']);     exit; }
    if (strlen($judul)  > 255){ echo json_encode(['success'=>false,'message'=>'Judul terlalu panjang']);       exit; }

    // cek poi exists
    $chk = $pdo->prepare('SELECT id FROM poi WHERE id = ?');
    $chk->execute([$poi_id]);
    if (!$chk->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Lokasi tidak ditemukan']);
        exit;
    }

    // satu user satu review per poi
    $dup = $pdo->prepare('SELECT id FROM poi_reviews WHERE poi_id = ? AND user_id = ?');
    $dup->execute([$poi_id, $user_id]);
    if ($dup->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Kamu sudah pernah mereview tempat ini']);
        exit;
    }

    // insert
    $ins = $pdo->prepare('
        INSERT INTO poi_reviews (poi_id, user_id, rating, judul, cerita)
        VALUES (?, ?, ?, ?, ?)
    ');
    $ins->execute([
        $poi_id,
        $user_id,
        $rating,
        $judul ?: null,
        $cerita,
    ]);

    echo json_encode(['success' => true, 'message' => 'Review berhasil dikirim!']);
    exit;
}

// method lain
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);