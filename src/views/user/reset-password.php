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
        <h2>Link tidak valid</h2>
        <p class="text-muted small mb-4">
          Link reset password sudah kadaluarsa atau tidak valid.
        </p>
        <a href="/forgot-password" class="btn btn-outline-primary">Minta link baru</a>
      </div>
    </div>
    <?php else : ?>
    <form class="bg-surface mx-auto my-5 row g-2">
      <div class="col-12">
        <h1 class="h3">Buat password baru</h1>
        <p class="text-muted">
          Masukkan password baru untuk akunmu.
        </p>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          Password baru
        </label>
        <div class="input-group">
          <input type="password" id="rp-pw" class="form-control password" placeholder="Min. 8 karakter">
          <button class="btn" type="button" id="toggle-pw">
            <i class="fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          Konfirmasi password
        </label>
        <div class="input-group">
          <input type="password" id="rp-pw-confirm" class="form-control password" placeholder="Ulangi password">
          <button class="btn" type="button" id="toggle-pw-confirm">
            <i class="fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12 py-4">
        <button class="btn btn-outline-primary" id="btn-rp">
          <span id="btn-rp-text">Simpan Password</span>
          <i id="btn-rp-spinner" class="d-none fa-solid fa-circle-notch fa-spin ms-1"></i>
        </button>
      </div>
    </form>
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
    const btnText = document.getElementById('btn-rp-text');
    const btnSpinner = document.getElementById('btn-rp-spinner');

    if (!password || !confirm) {
      flattyToast('error', 'Semua field wajib diisi.');
      return;
    }
    if (password.length < 8) {
      flattyToast('error', 'Password minimal 8 karakter.');
      return;
    }
    if (password !== confirm) {
      flattyToast('error', 'Konfirmasi password tidak cocok.');
      return;
    }
    btn.disabled = true;
    btnText.textContent = 'Menyimpan...';
    btnSpinner.classList.remove('d-none');
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
      flattyToast('success', 'Password berhasil diubah! Mengalihkan ke login...');
      btn.classList.add('d-none');
      setTimeout(() => window.location.href = '/login', 2000);
    } else {
      flattyToast('error', data.message ?? 'Gagal mengubah password.');
      btn.disabled = false;
      btnText.textContent = 'Simpan Password';
      btnSpinner.classList.add('d-none');
    }
  });
</script>