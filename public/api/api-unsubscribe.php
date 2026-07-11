<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
 header('Content-Type: application/json');

 try {
  validate_csrf();
  $email = trim($_POST["email"]);
  $subscriber = getSubscriberByEmail($email);

  if ($subscriber) {
   if (unsubscribeById($subscriber["id"])) {
    $status = "success";
    $message = "Email <strong>" . htmlspecialchars($email) . "</strong> berhasil dihentikan langganannya.";
    $subject = "Berhasil Unsubscribe - Ayokebandung.id";
    $msg = "<h3>Unsubscribe Berhasil</h3><p>Email <b>" . htmlspecialchars($email) . "</b> telah dihapus dari daftar newsletter kami.</p>";
    kirimEmailAyo($email, $subject, $msg);
   } else {
    $status = "error";
    $message = "Gagal memproses pembatalan langganan.";
   }
  } else {
   $status = "error";
   $message = "Email tidak ditemukan atau sudah tidak aktif.";
  }
 } catch (Exception $e) {
  $status = "error";
  $message = "Validasi gagal atau terjadi kesalahan sistem.";
 }

 echo json_encode(['status' => $status, 'message' => $message]);
 exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);