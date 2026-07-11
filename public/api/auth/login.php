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

$input = json_decode(file_get_contents('php://input'), true);
$identifier = trim($input['identifier'] ?? '');
$password = $input['password'] ?? '';

if (!$identifier || !$password) {
 echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
 exit;
}

$pdo = $GLOBALS['pdo'];

// cek by email atau username
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ? LIMIT 1");
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch();

if (!$user) {
 echo json_encode(['success' => false, 'message' => 'Akun tidak ditemukan.']);
 exit;
}

if (!$user['password']) {
 echo json_encode(['success' => false, 'message' => 'Akun ini terdaftar via Google. Silakan login dengan Google.']);
 exit;
}

if (!password_verify($password, $user['password'])) {
 echo json_encode(['success' => false, 'message' => 'Password salah.']);
 exit;
}

if (!$user['email_verified']) {
 echo json_encode(['success' => false, 'message' => 'Email belum diverifikasi. Cek inbox atau folder spam.']);
 exit;
}

$_SESSION['user'] = $user;
$_SESSION['flash'] = [
 'type' => 'success',
 'message' => 'Berhasil login sebagai ' . ($user['display_name'] ?? $user['name'])
];

$redirect = $_SESSION['redirect_after_login'] ?? '/';
unset($_SESSION['redirect_after_login']);
session_write_close();

echo json_encode(['success' => true, 'redirect' => $redirect]);
exit;