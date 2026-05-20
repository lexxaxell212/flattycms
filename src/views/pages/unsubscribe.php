<?php
$page_title = "Unsubscribe";

require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";

$status    = "";
$message   = "";
$show_form = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    validate_csrf();
    $email      = trim($_POST["email"]);
    $subscriber = getSubscriberByEmail($email);

    if ($subscriber) {
        if (unsubscribeById($subscriber["id"])) {
            $status  = "success";
            $message = "Email <strong>" . htmlspecialchars($email) . "</strong> berhasil dihentikan langganannya.";
            $subject = "Berhasil Unsubscribe - Ayokebandung.id";
            $msg     = "<h2>Unsubscribe Berhasil</h2><p>Email <b>$email</b> telah dihapus dari daftar newsletter kami.</p>";
            kirimEmailAyo($email, $subject, $msg);
        }
    } else {
        $status  = "error";
        $message = "Email tidak ditemukan atau sudah tidak aktif.";
    }

} elseif (isset($_GET["token"]) && !empty($_GET["token"])) {
    $token      = trim($_GET["token"]);
    $subscriber = getSubscriberByToken($token);

    if (!$subscriber) {
        $status  = "error";
        $message = "Token tidak valid atau sudah kadaluarsa.";
    } else {
        $now = date("Y-m-d H:i:s");
        if ($subscriber["token_expires"] && $subscriber["token_expires"] < $now) {
            $status    = "expired";
            $message   = "Token kadaluarsa. Silakan masukkan email Anda untuk unsubscribe.";
            $show_form = true;
        } else {
            if (unsubscribeById($subscriber["id"])) {
                $status  = "success";
                $message = "Berhasil unsubscribe.";
                $subject = "Berhasil Unsubscribe - Ayokebandung.id";
                $msg     = "<h2>Unsubscribe Berhasil</h2><p>Kamu tidak akan lagi menerima email dari kami.</p>";
                kirimEmailAyo($subscriber['email'], $subject, $msg);
            }
        }
    }
} else {
    $show_form = true;
}
?>

<style>
.card-unsubscribe { max-width: 450px; margin: auto; border: none; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
.icon-box { width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: -40px auto 20px; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
<main id="content" class="container-fluid">
<div class="container">
  <section id="Unsubscribe">
    <h1 class="text-title">
      Unsubscribe Newsletter
    </h1>
  </section>
  <div class="card card-unsubscribe p-4">
    <div class="card-body text-center">
      <div class="icon-box">
        <?php if ($status == "success"): ?>
          <i class="fa-solid fa-circle-check fa-3x text-success"></i>
        <?php elseif ($status == "error" && !$show_form): ?>
          <i class="fa-solid fa-circle-xmark fa-3x text-danger"></i>
        <?php else: ?>
          <i class="fa-solid fa-envelope-circle-check fa-3x text-primary"></i>
        <?php endif; ?>
      </div>

      <?php if ($message): ?>
        <div class="alert <?= $status == 'success' ? 'alert-success' : 'alert-danger' ?> border-0 small">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <?php if ($show_form): ?>
        <p class="text-muted small mb-4">Masukkan email Anda untuk berhenti berlangganan.</p>
        <form action="" method="POST">
          <div class="mb-3">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <input type="email" name="email" class="form-control form-control-lg fs-6" placeholder="nama@email.com" required>
          </div>
          <button type="submit" class="btn btn-primary w-100 fw-bold">
            Berhenti Berlangganan
          </button>
        </form>
      <?php endif; ?>

      <?php if ($status == "success" || ($status == "error" && !$show_form)): ?>
        <div class="mt-3">
          <a href="<?= BASE_URL ?>" class="btn btn-light w-100 text-muted">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Beranda
          </a>
        </div>
      <?php endif; ?>

      <p class="mt-4 mb-0 text-secondary" style="font-size: 0.75rem;">
        <?= date('Y') ?> <?= SITE_NAME ?>
      </p>
    </div>
  </div>
</div>
</main>