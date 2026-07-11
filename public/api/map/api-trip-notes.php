<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

$pdo = $GLOBALS['pdo'];
$user_id = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : null;

if (!$user_id) {
 http_response_code(401);
 echo json_encode(['success' => false, 'message' => 'Login diperlukan']);
 exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 http_response_code(405);
 echo json_encode(['success' => false, 'message' => 'Method not allowed']);
 exit;
}

verify_ajax_request('POST');
validate_csrf();

$action = $_POST['action'] ?? '';
$trip_item_id = (int)($_POST['trip_item_id'] ?? 0);

// ── ACTION: save (upsert) ────────────────────────────────────
if ($action === 'save') {
 $note = trim($_POST['note'] ?? '');

 if (!$trip_item_id || !$note) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'trip_item_id dan note wajib diisi']);
  exit;
 }

 try {
  // Pastikan trip_item milik user yang login
  $stmt = $pdo->prepare("
            SELECT ti.id FROM trip_items ti
            JOIN trips t ON t.id = ti.trip_id
            WHERE ti.id = ? AND t.user_id = ?
        ");
  $stmt->execute([$trip_item_id, $user_id]);
  if (!$stmt->fetch()) {
   http_response_code(403);
   echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
   exit;
  }

  $stmt = $pdo->prepare("
            INSERT INTO trip_notes (trip_item_id, user_id, note)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE note = VALUES(note), updated_at = NOW()
        ");
  $stmt->execute([$trip_item_id, $user_id, $note]);

  echo json_encode(['success' => true, 'message' => 'Catatan disimpan']);

 } catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Database error']);
 }
 exit;
}

// ── ACTION: delete ───────────────────────────────────────────
if ($action === 'delete') {
 $note_id = (int)($_POST['note_id'] ?? 0);

 if (!$note_id) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'note_id wajib']);
  exit;
 }

 try {
  $stmt = $pdo->prepare("DELETE FROM trip_notes WHERE id = ? AND user_id = ?");
  $stmt->execute([$note_id, $user_id]);

  if ($stmt->rowCount() === 0) {
   http_response_code(404);
   echo json_encode(['success' => false, 'message' => 'Catatan tidak ditemukan']);
   exit;
  }

  echo json_encode(['success' => true, 'message' => 'Catatan dihapus']);

 } catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Database error']);
 }
 exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Action tidak valid']);