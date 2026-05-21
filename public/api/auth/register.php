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

$input    = json_decode(file_get_contents('php://input'), true);
$name     = trim($input['name'] ?? '');
$username = trim($input['username'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$name || !$username || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Username tidak valid.']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password minimal 8 karakter.']);
    exit;
}

$pdo = $GLOBALS['pdo'];

// cek email & username sudah ada
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
$stmt->execute([$email, $username]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email atau username sudah digunakan.']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $username, $email, $hashed]);
$userId = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$_SESSION['user'] = [
    'id'           => $user['id'],
    'name'         => $user['name'],
    'display_name' => $user['display_name'],
    'email'        => $user['email'],
    'avatar'       => $user['avatar'],
    'username'     => $user['username'],
];

$_SESSION['flash'] = [
    'type'    => 'success',
    'message' => 'Akun berhasil dibuat! Selamat datang, ' . $name
];

session_write_close();
echo json_encode(['success' => true, 'redirect' => '/']);
exit;