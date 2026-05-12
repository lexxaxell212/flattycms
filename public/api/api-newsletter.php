<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$response = [
  "success" => false,
  "message" => "",
  "type" => "error",
  "email_value" => "",
];

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["email"])) {
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  
  if (!isValidEmailDomain($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response["message"] = "Email tidak valid atau domain tidak didukung!";
    $response["email_value"] = $_POST["email"];
  } else {
    $stmt = $pdo->prepare("
            INSERT INTO subscribers (email, status, subscribed_at) 
            VALUES (?, 'active', NOW())
            ON DUPLICATE KEY UPDATE 
                status = 'active', 
                subscribed_at = NOW(), 
                unsubscribe_token = NULL
        ");

    if ($stmt->execute([$email])) {
      $subscriber_id = $pdo->lastInsertId();
      if (!$subscriber_id) {
          $stmt_getId = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
          $stmt_getId->execute([$email]);
          $subscriber_id = $stmt_getId->fetchColumn();
      }

      $token = generateUnsubscribeToken($pdo, $subscriber_id);
      $unsub_link = "https://ayokebandung.id/unsubscribe.php?token=" . $token;

      $subject = "Konfirmasi Berlangganan - Ayokebandung.id";
      $message_html = "
        <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
            <h2 style='color: #1a2478;'>Halo! Terimakasih telah bergabung.</h2>
            <p>Email Anda <b>$email</b> telah terdaftar di newsletter Ayokebandung.id.</p>
            <p>Anda akan mendapatkan update seputar kuliner, wisata, dan budaya Bandung secara berkala.</p>
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='font-size: 12px; color: #999;'>
                Jika Anda merasa tidak melakukan pendaftaran ini, silakan 
                <a href='$unsub_link' style='color: #ef4444;'>Berhenti Berlangganan</a>.
            </p>
        </div>";

      kirimEmailAyo($email, $subject, $message_html);

      $response["success"] = true;
      $response["message"] = "Berhasil berlangganan! Cek inbox email Anda.";
      $response["type"] = "success";
    } else {
      $response["message"] = "Gagal menyimpan data!";
    }
  }
}

echo json_encode($response);

function isValidEmailDomain($email) {
  $allowed_domains = ["gmail.com", "googlemail.com", "yahoo.com", "ymail.com", "rocketmail.com", "outlook.com", "hotmail.com", "live.com", "icloud.com", "me.com", "protonmail.com", "proton.me"];
  $domain = strtolower(substr(strrchr($email, "@"), 1));
  return in_array($domain, $allowed_domains);
}
?>
