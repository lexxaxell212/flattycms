<?php
$status = "";
$message = "";
$show_form = false;

if (isset($_GET["token"]) && !empty($_GET["token"])) {
  $token = trim($_GET["token"]);
  $subscriber = getSubscriberByToken($token);
  
  if (!$subscriber) {
    $status = "error";
    $message = "Token tidak valid atau sudah kadaluarsa.";
  } else {
    $now = date("Y-m-d H:i:s");
    if ($subscriber["token_expires"] && $subscriber["token_expires"] < $now) {
      $status = "expired";
      $message = "Token kadaluarsa. Silakan masukkan email Anda untuk unsubscribe.";
      $show_form = true;
    } else {
      if (unsubscribeById($subscriber["id"])) {
        $status = "success";
        $message = "Berhasil unsubscribe.";
        $subject = "Berhasil Unsubscribe - Ayokebandung.id";
        $msg = "<h3>Unsubscribe Berhasil</h3><p>Kamu tidak akan lagi menerima email dari kami.</p>";
        kirimEmailAyo($subscriber['email'], $subject, $msg);
      }
    }
  }
} else {
  $show_form = true;
}
