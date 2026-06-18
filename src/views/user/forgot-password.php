<?php
$page_title = 'Lupa Password - ' . SITE_NAME;
?>
<main class="main-content">
  <div class="container">
    <div class="form mx-auto my-5 row g-2">
      <div class="col-12">
        <h1 class="h3" data-bhs="fp.title">Lupa password?</h1>
        <p class="text-muted" data-bhs="fp.excerpt">
          Masukkan emailmu dan kami akan kirim link reset password.
        </p>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-envelope me-1"></i>
          <span data-bhs="form.email.label">Email</span>
        </label>
        <input type="email" id="fp-email" class="form-control mb-4" data-bhs="form.email.placeholder" placeholder="nama@email.com">
      </div>
      <div class="col-12 py-4">
        <button type="button" class="btn btn-outline-primary" id="btn-fp">
          <span data-bhs="btn.link.reset">Kirim Link Reset</span>
        </button>
      </div>
    </div>
  </div>
</main>
<script>
  document.getElementById('btn-fp').addEventListener('click', async () => {
    const email = document.getElementById('fp-email').value.trim();
    const btn = document.getElementById('btn-fp');
    if (!email) {
      flattyToast('error', 'toast.email.required');
      return;
    }
    btn.disabled = true;
    btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
    await new Promise(r => setTimeout(r, 1000));
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
    btn.innerHTML = '<span data-bhs="btn.link.reset">Kirim Link Reset</span>';
    if (data.success) {
      flattyToast('success', 'toast.email.send.success');
      document.getElementById('fp-email').value = '';
    } else {
      flattyToast('error', data.message ?? 'toast.email.send.error');
    }
  });
</script>