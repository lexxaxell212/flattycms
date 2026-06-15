<?php
$page_title = "Unsubscribe";

require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";
require_once LIB_PATH . "v-unsubscribe.php";
?>
<main class="main-content">
  <div class="container">
    <div class="my-5">
      <div class="p-3 text-center">
        <?php if ($status == "success"): ?>
        <i class="fa-solid fa-circle-check fa-3x text-success mb-2"></i>
        <?php elseif ($status == "error" && !$show_form): ?>
        <i class="fa-solid fa-circle-xmark fa-3x text-danger mb-2"></i>
        <?php else : ?>
        <h1 class="h3 text-center">
          Unsubscribe Newsletter
        </h1>
        <?php endif; ?>
      </div>
      <?php if ($message): ?>
      <div class="text-center">
        <div class="badge <?= $status == 'success' ? 'badge-green' : 'badge-red' ?> small" style="max-width:740px">
          <?= $message ?>
        </div>
      </div>
      <?php endif; ?>
      <?php if ($show_form): ?>
      <form action="" method="POST" class="mx-auto row g-4 bg-surface">
        <div class="col-12">
          <p>
            Masukkan email Anda untuk berhenti berlangganan.
          </p>
        </div>
        <div class="col-12">
          <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
          <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-outline-primary mt-2">
            Berhenti Berlangganan
          </button>
        </div>
      </form>
      <?php endif; ?>
      <?php if ($status == "success" || ($status == "error" && !$show_form)): ?>
      <?php endif; ?>
    </div>
  </div>
</main>