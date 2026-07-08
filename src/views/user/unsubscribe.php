<?php
$page_title = "Unsubscribe";
$show_form = true;
$status = "";
$message = "";

require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";
require_once LIB_PATH . "v-unsubscribe.php"; 
?>
<main class="main-content">
  <div class="container">
    <div class="my-5">
      <div class="p-3 text-center mb-5">
        <h1 class="h2 text-center" data-bhs="unsub.title">
          Unsubscribe Newsletter
        </h1>
        <div id="successPlace"></div>
      </div>

      <?php if ($show_form): ?>
      <div id="unsubBody">
        <form id="unsubForm" action="/api/api-unsubscribe.php" method="POST" class="mx-auto row g-4 form">
          <div class="col-12">
            <p id="unsubExcerpt" data-bhs="unsub.excerpt">
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
      </div>
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
      if (!submitBtn) return;

      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true; 
      submitBtn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';

      try {
        await new Promise(r => setTimeout(r, 1000));
      } catch (err) {
        console.error(err);
      }

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
          const unsubBody = document.getElementById('unsubBody');
          const successPlace = document.getElementById('successPlace');
          
          if (unsubBody && successPlace) {
            unsubBody.remove();
            successPlace.innerHTML = '<p class="text-success">Unsubscribe berhasil. Terima kasih telah berlangganan.</p>';
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        flattyToast('error', 'Terjadi kesalahan sistem, silakan coba lagi.');
      })
      .finally(() => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      });
    });
  }
});
</script>
