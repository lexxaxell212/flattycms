<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

verify_ajax_request();

$csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (!verify_csrf_token($csrf)) {
 http_response_code(403);
 echo json_encode(['success' => false, 'message' => 'Invalid request.']);
 exit;
}

$config = require_once dirname(__DIR__, 3) . "/config/oauth.php";

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? '';
if (!$token) {
 echo json_encode(['success' => false]); exit;
}

// Verifikasi JWT token dari Google
$response = file_get_contents(
 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $token
);
$profile = json_decode($response, true);

// Validasi client_id
if (($profile['aud'] ?? '') !== $config['client_id']) {
 echo json_encode(['success' => false]);
 exit;
}

$pdo = $GLOBALS['pdo'];

// Cek user exist (sama seperti callback.php mu)
$stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
$stmt->execute([$profile['sub']]);
$user = $stmt->fetch();

if (!$user) {
 $stmt = $pdo->prepare("INSERT INTO users (google_id, name, email, avatar) VALUES (?, ?, ?, ?)");
 $stmt->execute([$profile['sub'], $profile['name'], $profile['email'], $profile['picture']]);

 $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
 $stmt->execute([$profile['sub']]);
 $user = $stmt->fetch();
}

$_SESSION['user'] = $user;
$_SESSION['flash'] = [
 'type' => 'success',
 'message' => 'Berhasil login sebagai ' . ($user['display_name'] ?? $user['name'])
];
session_write_close();

echo json_encode([
 'success' => true,
 'redirect' => $_SESSION['redirect_after_login'] ?? '/'
]);
exit;