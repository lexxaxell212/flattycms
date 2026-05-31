<?php
$page_title = 'Lupa Password — ' . SITE_NAME;
?>
<main id="content">
<div class="container">
<div>
  <section id="lupa-password">
    <a href="/login" class="small mb-3 d-inline-flex align-items-center gap-1">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali ke login
    </a>
    <h2>Lupa password?</h2>
    <p class="text-muted">Masukkan emailmu dan kami akan kirim link reset password.</p>
  </section>
    <div class="mb-4">
        <label class="form-label mb-2">
          <i class="fas fa-envelope me-1"></i>
          Email
        </label>
        <input type="email" id="fp-email" class="form-control" placeholder="nama@email.com">
    </div>
    <button class="btn btn-outline-primary" id="btn-fp">
        <span id="btn-fp-text">Kirim Link Reset</span>
        <i id="btn-fp-spinner" class="d-none fa-solid fa-circle-notch fa-spin ms-1"></i>
    </button>
</div>
</div>
</main>
<script>
document.getElementById('btn-fp').addEventListener('click', async () => {
    const email      = document.getElementById('fp-email').value.trim();
    const btn        = document.getElementById('btn-fp');
    const btnText    = document.getElementById('btn-fp-text');
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
        body: JSON.stringify({ email })
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