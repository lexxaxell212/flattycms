<?php
$page_title = "Unsubscribe";

require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";
require_once LIB_PATH . "v-unsubscribe.php";
?>
<main id="content">
<div class="container">
  <section id="Unsubscribe">
  <div class="card card-unsubscribe p-4">
    <div class="card-body text-center">
      <div class="p-2">
        <?php if ($status == "success"): ?>
          <i class="fa-solid fa-circle-check fa-3x text-success mb-2"></i>
        <?php elseif ($status == "error" && !$show_form): ?>
          <i class="fa-solid fa-circle-xmark fa-3x text-danger mb-2"></i>
        <?php else: ?>
          <h2 class="text-center">
            Unsubscribe Newsletter
          </h2>
        <?php endif; ?>
      </div>
      <?php if ($message): ?>
        <div class="alert <?= $status == 'success' ? 'alert-success' : 'alert-danger' ?> border-0 small">
          <?= $message ?>
        </div>
      <?php endif; ?>
      <?php if ($show_form): ?>
        <p class="text-start mb-4">Masukkan email Anda untuk berhenti berlangganan.</p>
        <form action="" method="POST">
          <div class="mb-4">
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
  </section>
</div>
</main>