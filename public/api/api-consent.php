<?php
require_once __DIR__ . "/../bootstrap.php";
autoload_core();

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
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);

    // Set cookies (1 tahun)
    $expire = time() + (365 * 24 * 3600);
    
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