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

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
$stmt->execute([$email, $username]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email atau username sudah digunakan.']);
    exit;
}

$hashed        = password_hash($password, PASSWORD_DEFAULT);
$verify_token  = bin2hex(random_bytes(32));
$verify_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

$stmt = $pdo->prepare("INSERT INTO users (name, username, email, password, email_verified, verify_token, verify_expires) VALUES (?, ?, ?, ?, 0, ?, ?)");
$stmt->execute([$name, $username, $email, $hashed, $verify_token, $verify_expires]);

require_once LIB_PATH . "mailer.php";
$verify_link  = BASE_URL . "api/auth/verify-email.php?token=" . $verify_token;
$subject      = 'Verifikasi Email - ' . SITE_NAME;
$message_html = "
<div style='font-family:sans-serif;max-width:600px;margin:0 auto;padding:20px;border:1px solid #eee;border-radius:10px;'>
    <h2 style='color:#7c3aed;'>Halo, {$name}!</h2>
    <p>Terima kasih udah daftar di <b>" . SITE_NAME . "</b>.</p>
    <p>Klik tombol di bawah untuk verifikasi emailmu:</p>
    <a href='{$verify_link}' style='display:inline-block;padding:12px 24px;background:#7c3aed;color:#fff;border-radius:8px;text-decoration:none;font-weight:bold;'>Verifikasi Email</a>
    <p style='margin-top:20px;color:#888;font-size:13px;'>Link berlaku 24 jam. Abaikan email ini jika kamu tidak merasa mendaftar.</p>
</div>";
kirimEmailAyo($email, $subject, $message_html);

echo json_encode(['success' => true, 'message' => 'Akun berhasil dibuat! Cek emailmu untuk verifikasi.', 'redirect' => null]);
exit;