<?php
$dir = __DIR__;
if (file_exists($dir . '/../vendor/autoload.php')) {
    require_once $dir . '/../vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists("autoload_core")) {
  function autoload_core()
  {
    $dir = __DIR__;
    foreach (["", "../", "../../"] as $level) {
      $setup = $dir . "/" . $level . "setup.php";
      $conf = $dir . "/" . $level . "config.php";
      if (file_exists($setup)) {
        require_once $setup;
      }
      if (file_exists($conf)) {
        require_once $conf;
      }
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

function kirimEmailAyo($ke, $subjek, $pesan_html) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password = SMTP_PASS;
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

function generateUnsubscribeToken($pdo, $subscriber_id)
{
    $token = bin2hex(random_bytes(32)); 
    $expires = date("Y-m-d H:i:s", strtotime("+5 years"));

    $stmt = $pdo->prepare(
        "UPDATE subscribers SET unsubscribe_token = ?, token_expires = ? WHERE id = ?"
    );
    $stmt->execute([$token, $expires, $subscriber_id]);

    return $token;
}
