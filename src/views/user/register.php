<?php
$page_title = 'Daftar - ' . SITE_NAME;
?>
<main class="main-content" id="show-gsi">
  <div class="container">
    <div class="form mx-auto my-5 row g-2">
      <div class="col-12">
        <h1 class="h3" data-bhs="reg.title">Buat akun baru</h1>
        <p class="text-muted" data-bhs="reg.excerpt">
          Isi data di bawah untuk mendaftar
        </p>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-circle-user me-1"></i>
          <span data-bhs="form.name.label">Nama lengkap</span>
        </label>
        <div class="input-group">
          <input type="text" id="reg-name" class="form-control" data-bhs="form.name.placeholder" placeholder="Nama" required>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-circle-user me-1"></i>
          <span data-bhs="form.username.label">Username</span>
        </label>
        <div class="input-group mb-1">
          <input type="text" id="reg-username" class="form-control" data-bhs="form.username.placeholder" placeholder="Nama pengguna" required>
        </div>
        <div class="form-text text-muted">
          <span data-bhs="form.username.desc">Hanya huruf, angka, dan underscore.</span>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-at me-1"></i>
          <span data-bhs="form.email.label">Email</span></label>
        <div class="input-group">
          <input type="email" id="reg-email" class="form-control" data-bhs="form.email.placeholder" placeholder="nama@email.com" required>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          <span data-bhs="form.password.label">Password</span>
        </label>
        <div class="input-group">
          <input type="password" id="reg-pw-input" class="form-control" data-bhs="form.password.placeholder" placeholder="Min. 8 karakter">
          <button class="btn" type="button" id="toggle-pw-reg">
            <i class="text-muted fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          <span data-bhs="form.password.repeat label">Konfirmasi password</span>
        </label>
        <div class="input-group">
          <input type="password" id="reg-pw-confirm" class="form-control" data-bhs="form.password.repeat.placeholder" placeholder="Ulangi kata sandi">
          <button class="btn" type="button" id="toggle-pw-confirm">
            <i class="text-muted fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12 py-4">
        <button type="button" class="btn btn-primary" id="btn-register">
          <span data-bhs="btn.reg">Daftar</span>
        </button>
      </div>
    </div>
    <div class="text-center text-muted small">
      <span data-bhs="have.account">Sudah punya akun?</span> <a href="/login" data-bhs="btn.login">Masuk</a>
    </div>
  </div>
</main>
<script>
  function togglePwReg(inputId, btnId) {
    document.getElementById(btnId).addEventListener('click', () => {
      const input = document.getElementById(inputId);
      const icon = document.querySelector(`#${btnId} i`);
      input.type = input.type === 'password' ? 'text': 'password';
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  }
  togglePwReg('reg-pw-input', 'toggle-pw-reg');
  togglePwReg('reg-pw-confirm', 'toggle-pw-confirm');

  document.getElementById('btn-register').addEventListener('click', async () => {
    const name = document.getElementById('reg-name').value.trim();
    const username = document.getElementById('reg-username').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const password = document.getElementById('reg-pw-input').value;
    const confirm = document.getElementById('reg-pw-confirm').value;
    const btn = document.getElementById('btn-register');

    if (!name || !username || !email || !password || !confirm) {
      flattyToast('error', 'toast.form.error');
      return;
    }
    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
      flattyToast('error', 'toast.username.required');
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
    const res = await fetch('/api/auth/register.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': CONFIG.csrfToken
      },
      body: JSON.stringify({
        name, username, email, password
      })
    });
    const data = await res.json();
    btn.disabled = false;
    btn.innerHTML = '<span data-bhs="btn.reg">Daftar</span>';
    if (data.success) {
      flattyToast('success', 'toast.new.account.success');
    } else {
      flattyToast('error', data.message ?? 'toast.new.account.error');
    }
  });
</script>