<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$consent_given = isset($data['consent_given']) ? $data['consent_given'] : true;
$categories = $data['categories'] ?? [];

if (!is_array($categories)) {
  $categories = [];
}

$ip = $_SERVER['HTTP_CF_CONNECTING_IP']
   ?? $_SERVER['HTTP_X_FORWARDED_FOR']
   ?? $_SERVER['REMOTE_ADDR'];

$pdo = $GLOBALS['pdo'];

try {
  $stmt = $pdo->prepare("
        INSERT INTO user_consents
        (session_id, consent_given, categories, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
  $stmt->execute([
    session_id(),
    $consent_given ? 1 : 0,
    json_encode($categories),
    $ip,
    $_SERVER['HTTP_USER_AGENT'] ?? ''
  ]);

  $expire = time() + (13 * 30 * 24 * 3600);

  setcookie('consent_accepted', $consent_given ? '1' : '0', $expire, '/', '', true, true);
  setcookie('consent_categories', json_encode($categories), $expire, '/', '', true, false);

  echo json_encode(['success' => true]);

} catch (Exception $e) {
  http_response_code(500);
  $msg = defined('APP_ENV') && APP_ENV === 'development'
    ? $e->getMessage()
    : 'Terjadi kesalahan.';
  echo json_encode(['error' => $msg]);
}