<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();
verify_ajax_request();
require_once LIB_PATH . "mailer.php";

$csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (!verify_csrf_token($csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
    exit;
}

$pdo  = $GLOBALS['pdo'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !$user['password']) {
    echo json_encode(['success' => true]);
    exit;
}

$token   = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $pdo->prepare("
    INSERT INTO password_resets (email, token, expires_at) 
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)
");
$stmt->execute([$email, $token, $expires]);

$resetLink = rtrim(BASE_URL, '/') . '/reset-password?token=' . $token;
$subject = 'Reset Password - ' . SITE_NAME;
$message_html = "
<div style='font-family:sans-serif;max-width:600px;margin:0 auto;padding:20px;border:1px solid #eee;border-radius:10px;'>
    <h2 style='color:#1a2478;'>Reset Password</h2>
    <p>Kami menerima permintaan reset password untuk akun <b>{$email}</b>.</p>
    <p>Klik tombol di bawah untuk membuat password baru (berlaku 1 jam):</p>
    <a href='{$resetLink}' style='display:inline-block;margin:16px 0;padding:12px 24px;background:#1a2478;color:#fff;border-radius:8px;text-decoration:none;font-weight:500;'>Reset Password</a>
    <hr style='border:0;border-top:1px solid #eee;margin:20px 0;'>
    <p style='font-size:12px;color:#999;'>Abaikan email ini jika kamu tidak merasa melakukan permintaan ini.</p>
</div>";

$result = kirimEmailAyo($email, $subject, $message_html);

if (!$result) {
    error_log("[forgot-password] gagal kirim ke: {$email}");
}

echo json_encode(['success' => true]);
exit;