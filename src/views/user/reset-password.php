<?php
$token = trim($_GET['token'] ?? '');
$invalid = true;

if ($token) {
  $pdo = $GLOBALS['pdo'];
  $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1");
  $stmt->execute([$token]);
  $reset = $stmt->fetch();
  if ($reset) $invalid = false;
}
$page_title = 'Reset Password - ' . SITE_NAME;
?>
<main class="main-content">
  <div class="container">
    <?php if ($invalid): ?>
    <div id="reset-pw-form">
      <div class="bg-surface my-5 py-5 text-center">
        <i class="fa-solid fa-circle-xmark fa-3x text-danger mb-4"></i>
        <h2 data-bhs="rp.invalid">Link tidak valid</h2>
        <p class="text-muted small mb-4" data-bhs="rp.desc">
          Link reset password sudah kadaluarsa atau tidak valid.
        </p>
        <a href="/forgot-password" class="btn btn-primary" data-bhs="btn.link.reset.request">Minta link baru</a>
      </div>
    </div>
    <?php else : ?>
    <div class="form mx-auto my-5 row g-2">
      <div class="col-12">
        <h1 class="h3" data-bhs="rp.title">Buat password baru</h1>
        <p class="text-muted" data-bhs="rp.excerpt">
          Masukkan password baru untuk akunmu.
        </p>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          <span data-bhs="form.password.new.label">Kata sandi baru</span>
        </label>
        <div class="input-group">
          <input type="password" id="rp-pw" class="form-control password" data-bhs="form.password.placeholder" placeholder="Min. 8 karakter">
          <button class="btn btn-fit" type="button" id="toggle-pw">
            <i class="fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          <span data-bhs="form.password.repeat.label">Konfirmasi kata sandi</span>
        </label>
        <div class="input-group">
          <input type="password" id="rp-pw-confirm" class="form-control password" data-bhs="form.password.repeat.placeholder" placeholder="Ulangi kata sandi">
          <button class="btn btn-fit" type="button" id="toggle-pw-confirm">
            <i class="fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12 py-4">
        <button type="button" class="btn btn-outline-primary" id="btn-rp">
          <span data-bhs="btn.save">Simpan</span>
        </button>
      </div>
    </div>
    <?php endif; ?>
  </div>
</main>
<script>
  function togglePw(inputId, btnId) {
    document.getElementById(btnId)?.addEventListener('click', () => {
      const input = document.getElementById(inputId);
      const icon = document.querySelector('#' + btnId + ' i');
      input.type = input.type === 'password' ? 'text': 'password';
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  }
  togglePw('rp-pw', 'toggle-pw');
  togglePw('rp-pw-confirm', 'toggle-pw-confirm');

  document.getElementById('btn-rp')?.addEventListener('click', async () => {
    const password = document.getElementById('rp-pw').value;
    const confirm = document.getElementById('rp-pw-confirm').value;
    const btn = document.getElementById('btn-rp');

    if (!password || !confirm) {
      flattyToast('error', 'toast.form.error');
      return;
    }
    if (password.length < 8) {
      flattyToast('error', 'toast.password.required');
      return;
    }
    if (password !== confirm) {
      flattyToast('error', 'toast.password.confirm.required');
      return;
    }
    btn.disabled = true;
    btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
    await new Promise(r => setTimeout(r, 1000));
    const res = await fetch('/api/auth/reset-password.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': CONFIG.csrfToken
      },
      body: JSON.stringify({
        token: '<?= safe_html($token) ?>', password
      })
    });
    const data = await res.json();
    if (data.success) {
      flattyToast('success', 'toast.reset.password.success');
      btn.classList.add('d-none');
      setTimeout(() => window.location.href = '/login', 2000);
    } else {
      flattyToast('error', data.message ?? 'toast.password.error');
      btn.disabled = false;
      btn.innerHTML = '<span data-bhs="btn.save">Simpan</span>';
    }
  });
</script>