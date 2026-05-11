<?php
$dir = __DIR__;
if (file_exists($dir . '/../vendor/autoload.php')) {
    require_once $dir . '/../vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists("autoload_core")) {
    function autoload_core() {
      $dir = __DIR__;
       foreach (["", "../config/", "../../config/" as $level) {
         $constants = $dir . "/" . $level . "constants.php";
         $db  = $dir . "/" . $level . "db.php";
         $key  = $dir . "/" . $level . "key.php";
        if (file_exists($constants)) require_once $constants;
        if (file_exists($db))  require_once $db;
        if (file_exists($key)) require_once $key;
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        "cookie_httponly" => true,
        "cookie_secure"   => isset($_SERVER["HTTPS"]),
        "cookie_samesite" => "Lax",
    ]);
}

// ========== CSRF ==========

function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function regenerate_csrf_token(): void {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verify_csrf_token(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function validate_csrf(): void {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals((string)$_SESSION['csrf_token'], (string)$_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Invalid CSRF token. Silakan refresh halaman dan coba lagi.');
    }
}

// ========== HELPER ==========

function safe_html($value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// ========== MAIL ==========

function kirimEmailAyo($ke, $subjek, $pesan_html) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setFrom(SMTP_USER, SITE_NAME);
        $mail->addAddress($ke);
        $mail->isHTML(true);
        $mail->Subject = $subjek;
        $mail->Body    = $pesan_html;
        $mail->AltBody = strip_tags($pesan_html);
        return $mail->send();
    } catch (Exception $e) {
        if (defined('LOGS_PATH')) {
            error_log("[" . date('Y-m-d H:i:s') . "] Mail Error: " . $mail->ErrorInfo . PHP_EOL, 3, LOGS_PATH . "php_errors.log");
        }
        return false;
    }
}

function generateUnsubscribeToken($pdo, $subscriber_id) {
    $token   = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+5 years"));
    $stmt    = $pdo->prepare("UPDATE subscribers SET unsubscribe_token = ?, token_expires = ? WHERE id = ?");
    $stmt->execute([$token, $expires, $subscriber_id]);
    return $token;
}