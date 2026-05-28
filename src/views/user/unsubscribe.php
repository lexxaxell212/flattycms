<?php
$page_title = "Unsubscribe";

require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";
require_once LIB_PATH . "v-unsubscribe.php";
?>

<main id="content">
<div class="container">
  <section id="Unsubscribe">
    <h2 class="text-center">
      Unsubscribe Newsletter
    </h2>
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
            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
          </div>
          <button type="submit" class="btn btn-outline-primary w-100">
            Berhenti Berlangganan
          </button>
        </form>
      <?php endif; ?>

      <?php if ($status == "success" || ($status == "error" && !$show_form)): ?>
      <?php endif; ?>
    </div>
  </div>
</div>
</main>