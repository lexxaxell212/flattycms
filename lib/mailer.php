<?php
// PHPMailer
if (file_exists(ROOT_PATH . "/vendor/autoload.php")) {
  require_once ROOT_PATH . "/vendor/autoload.php";
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function kirimEmailAyo($ke, $subjek, $pesan_html)
{
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom(SMTP_USER, SITE_NAME);
    $mail->addAddress($ke);
    $mail->isHTML(true);
    $mail->Subject = $subjek;
    $mail->Body = $pesan_html;
    $mail->AltBody = strip_tags($pesan_html);
    return $mail->send();
  } catch (Exception $e) {
    if (defined("LOGS_PATH")) {
      error_log(
        "[" .
          date("Y-m-d H:i:s") .
          "] Mail Error: " .
          $mail->ErrorInfo .
          PHP_EOL,
        3,
        LOGS_PATH . "php_errors.log"
      );
    }
    return false;
  }
}
