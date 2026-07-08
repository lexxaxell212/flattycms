<?php
$page_title = "Unsubscribe";
require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";
require_once LIB_PATH . "v-unsubscribe.php"; 
?>
<main class="main-content">
  <div class="container">
    <div class="my-5">
      <div class="p-3 text-center mb-4">
        <h1 class="h2 text-center" data-bhs="unsub.title">
          Unsubscribe Newsletter
        </h1>
      </div>

      <?php if ($show_form): ?>
      <form id="unsubForm" action="/api/api-unsubscribe.php" method="POST" class="mx-auto row g-4 form">
        <div class="col-12">
          <p data-bhs="unsub.excerpt">
            Masukkan email Anda untuk berhenti berlangganan.
          </p>
        </div>
        <div class="col-12">
          <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
          <input type="email" name="email" class="form-control" data-bhs="form.email.placeholder" placeholder="nama@email.com" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary mt-2" data-bhs="btn.unsubs">
            Berhenti langganan
          </button>
        </div>
      </form>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
  <?php if (!empty($status) && !empty($message)): ?>
    flattyToast('<?= $status ?>', '<?= addslashes($message) ?>');
  <?php endif; ?>

  const form = document.getElementById('unsubForm');
  if (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      
      const submitBtn = form.querySelector('button[type="submit"]');
      submitBtn.disabled = true; 

      submitBtn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';

      const formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(data => {
        flattyToast(data.status, data.message);
        
        if (data.status === 'success') {
          form.reset(); 
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        flattyToast('error', 'Terjadi kesalahan sistem, silakan coba lagi.');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Berhenti langganan';
      });
    });
  }
});
</script>
