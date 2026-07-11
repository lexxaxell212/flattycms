<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');
validate_csrf();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
 echo json_encode(['success' => false, 'message' => 'Login dulu ya']);
 exit;
}

$user_id = $_SESSION['user']['id'];
$content_type = $_POST['content_type'] ?? '';
$content_id = (int)($_POST['content_id'] ?? 0);

if (!$content_type || !$content_id) {
 echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
 exit;
}

$pdo = $GLOBALS['pdo'];

$stmt = $pdo->prepare("SELECT id FROM reactions WHERE user_id=? AND content_type=? AND content_id=?");
$stmt->execute([$user_id, $content_type, $content_id]);
$existing = $stmt->fetch();

if ($existing) {
 $pdo->prepare("DELETE FROM reactions WHERE id=?")->execute([$existing['id']]);
 $liked = false;
} else {
 $pdo->prepare("INSERT INTO reactions (user_id, content_type, content_id) VALUES (?,?,?)")->execute([$user_id, $content_type, $content_id]);
 $liked = true;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM reactions WHERE content_type=? AND content_id=?");
$stmt->execute([$content_type, $content_id]);
$count = $stmt->fetchColumn();

echo json_encode(['success' => true, 'liked' => $liked, 'count' => $count]);