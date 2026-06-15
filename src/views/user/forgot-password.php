<?php
$page_title = 'Lupa Password - ' . SITE_NAME;
?>
<main class="main-content">
  <div class="container">
    <form class="bg-surface mx-auto my-5 row g-2">
    <div class="col-12">
      <h1 class="h3">Lupa password?</h1>
      <p class="text-muted">
        Masukkan emailmu dan kami akan kirim link reset password.
      </p>
    </div>
    <div class="col-12">
      <label class="form-label">
        <i class="fas fa-envelope me-1"></i>
        Email
      </label>
      <input type="email" id="fp-email" class="form-control mb-4" placeholder="nama@email.com">
    </div>
    <div class="col-12 py-4">
      <button class="btn btn-outline-primary" id="btn-fp">
        <span id="btn-fp-text">Kirim Link Reset</span>
        <i id="btn-fp-spinner" class="d-none fa-solid fa-circle-notch fa-spin ms-2"></i>
      </button>
    </div>
    </form>
  </div>
</main>
<script>
  document.getElementById('btn-fp').addEventListener('click', async () => {
    const email = document.getElementById('fp-email').value.trim();
    const btn = document.getElementById('btn-fp');
    const btnText = document.getElementById('btn-fp-text');
    const btnSpinner = document.getElementById('btn-fp-spinner');
    if (!email) {
      flattyToast('error', 'Email wajib diisi.');
      return;
    }
    btn.disabled = true;
    btnText.textContent = 'Mengirim...';
    btnSpinner.classList.remove('d-none');
    const res = await fetch('/api/auth/forgot-password.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': CONFIG.csrfToken
      },
      body: JSON.stringify({
        email
      })
    });
    const data = await res.json();
    btn.disabled = false;
    btnText.textContent = 'Kirim Link Reset';
    btnSpinner.classList.add('d-none');
    if (data.success) {
      flattyToast('success', 'Link reset password sudah dikirim. Cek inbox atau folder spam.');
      document.getElementById('fp-email').value = '';
    } else {
      flattyToast('error', data.message ?? 'Gagal mengirim email.');
    }
  });
</script>