<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo    = $GLOBALS['pdo'];
$method = $_SERVER['REQUEST_METHOD'];

// Semua endpoint butuh login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Login diperlukan']);
    exit;
}

verify_ajax_request('POST');
$input   = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$user_id = (int)$_SESSION['user_id'];

if (!verify_csrf_token($input['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

// ── POST — Tambah atau update catatan (upsert) ────────────────
// Kalau sudah ada note di trip_item ini → update, kalau belum → insert
if ($method === 'POST') {
    $trip_item_id = (int)($input['trip_item_id'] ?? 0);
    $note         = trim($input['note'] ?? '');

    if (!$trip_item_id || !$note) {
        http_response_code(400);
        echo json_encode(['error' => 'trip_item_id dan note wajib diisi']);
        exit;
    }

    try {
        // Pastikan trip_item ini milik user yang login
        $stmt = $pdo->prepare("
            SELECT ti.id FROM trip_items ti
            JOIN trips t ON t.id = ti.trip_id
            WHERE ti.id = ? AND t.user_id = ?
        ");
        $stmt->execute([$trip_item_id, $user_id]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo json_encode(['error' => 'Akses ditolak']);
            exit;
        }

        // Upsert
        $stmt = $pdo->prepare("
            INSERT INTO trip_notes (trip_item_id, user_id, note)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE note = VALUES(note), updated_at = NOW()
        ");
        $stmt->execute([$trip_item_id, $user_id, $note]);
        $note_id = $pdo->lastInsertId() ?: null;

        echo json_encode(['success' => true, 'note_id' => $note_id, 'message' => 'Catatan disimpan']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

// ── DELETE — Hapus catatan ────────────────────────────────────
if ($method === 'DELETE') {
    $note_id = (int)($input['note_id'] ?? 0);

    if (!$note_id) {
        http_response_code(400);
        echo json_encode(['error' => 'note_id wajib']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM trip_notes WHERE id = ? AND user_id = ?");
        $stmt->execute([$note_id, $user_id]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Catatan tidak ditemukan']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Catatan dihapus']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);