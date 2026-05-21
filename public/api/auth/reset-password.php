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
$token    = trim($input['token'] ?? '');
$password = $input['password'] ?? '';

if (!$token || !$password) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}
if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password minimal 8 karakter.']);
    exit;
}

$pdo  = $GLOBALS['pdo'];
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    echo json_encode(['success' => false, 'message' => 'Link tidak valid atau sudah kadaluarsa.']);
    exit;
}

// update password
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt   = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->execute([$hashed, $reset['email']]);

// hapus token
$stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
$stmt->execute([$token]);

// kirim notif email
require_once LIB_PATH . "mailer.php";
$subject      = 'Password Berhasil Diubah — ' . SITE_NAME;
$message_html = "
<div style='font-family:sans-serif;max-width:600px;margin:0 auto;padding:20px;border:1px solid #eee;border-radius:10px;'>
    <h2 style='color:#1a2478;'>Password kamu berhasil diubah</h2>
    <p>Password akun <b>{$reset['email']}</b> baru saja diubah.</p>
    <p>Jika kamu tidak merasa melakukan perubahan ini, segera hubungi kami.</p>
</div>";
kirimEmailAyo($reset['email'], $subject, $message_html);

echo json_encode(['success' => true]);
exit;