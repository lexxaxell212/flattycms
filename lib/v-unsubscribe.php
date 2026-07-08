<?php
$status = "";
$message = "";
$show_form = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
  validate_csrf();
  $email = trim($_POST["email"]);
  $subscriber = getSubscriberByEmail($email);
  if ($subscriber) {
    if (unsubscribeById($subscriber["id"])) {
      $status = "success";
      $message = "Email <strong>" . htmlspecialchars($email) . "</strong> berhasil dihentikan langganannya.";
      $subject = "Berhasil Unsubscribe - Ayokebandung.id";
      $msg = "<h2>Unsubscribe Berhasil</h2><p>Email <b>" . htmlspecialchars($email) . "</b> telah dihapus dari daftar newsletter kami.</p>";
      kirimEmailAyo($email, $subject, $msg);
    }
  } else {
    $status = "error";
    $message = "Email tidak ditemukan atau sudah tidak aktif.";
  }

  $_SESSION['unsub_status'] = $status;
  $_SESSION['unsub_message'] = $message;
  // header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
  // exit;

} elseif (isset($_GET["token"]) && !empty($_GET["token"])) {
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
        $msg = "<h2>Unsubscribe Berhasil</h2><p>Kamu tidak akan lagi menerima email dari kami.</p>";
        kirimEmailAyo($subscriber['email'], $subject, $msg);
      }
    }
  }
} elseif (isset($_SESSION['unsub_status'])) {
  $status = $_SESSION['unsub_status'];
  $message = $_SESSION['unsub_message'];
  unset($_SESSION['unsub_status'], $_SESSION['unsub_message']);
} else {
  $show_form = true;
}