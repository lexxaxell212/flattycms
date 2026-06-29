<?php
if (isset($_SESSION['user'])) {
  header('Location: /profile');
  exit;
}
?>
<main class="main-content" id="show-gsi">
  <div class="container">
    <div class="form mx-auto my-5 row g-2">
      <div class="col-12">
        <h1 class="h3" data-bhs="login.title">Selamat datang</h1>
        <p class="text-muted" data-bhs="login.excerpt">
          Masuk ke akun kamu
        </p>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-at me-1"></i>
          <span data-bhs="form.email.username.label">Email atau username</span>
        </label>
        <input type="text" id="login-id" class="form-control" data-bhs="form.email.placeholder" placeholder="nama@email.com">
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          <span data-bhs="form.password.label">Password</span>
        </label>
        <div class="input-group">
          <input type="password" id="login-pw" class="form-control password" placeholder="••••••••">
          <button class="btn text-muted btn-fit" type="button" id="toggle-pw">
            <i class="fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12 py-4">
        <button type="button" class="btn btn-primary" id="btn-login">
          <span data-bhs="btn.login">Masuk</span>
        </button>
      </div>
    </div>
    <div class="d-flex justify-content-center mb-4">
      <a href="/forgot-password" class="small" data-bhs="fp.title">Lupa password?</a>
    </div>
    <div class="divider">
      <span data-bhs="or">atau</span>
    </div>
    <div class="d-flex justify-content-center">
      <div id="google-login-page"></div>
    </div>
    <p class="text-center text-muted small mt-2">
      <span data-bhs="not.have.account">Belum punya akun?</span> <a href="/register" class="fw-medium" data-bhs="btn.reg">Daftar</a>
    </p>
  </div>
</main>
<script>
  document.getElementById('toggle-pw').addEventListener('click', () => {
    const pw = document.getElementById('login-pw');
    const icon = document.querySelector('#toggle-pw i');
    pw.type = pw.type === 'password' ? 'text': 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  });
  document.getElementById('btn-login').addEventListener('click', async () => {
    const identifier = document.getElementById('login-id').value.trim();
    const password = document.getElementById('login-pw').value;
    const btn = document.getElementById('btn-login');

    if (!identifier || !password) {
      flattyToast('error', 'toast.email.password.required');
      return;
    }
    btn.disabled = true;
    btn.innerHTML = '<div class="btn-fetch"><span></span><span></span><span></span></div>';
    await new Promise(r => setTimeout(r, 1000));
    const res = await fetch('/api/auth/login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': CONFIG.csrfToken
      },
      body: JSON.stringify({
        identifier, password
      })
    });
    const data = await res.json();
    btn.disabled = false;
    btn.innerHTML = '<span data-bhs="btn.login">Masuk</span>';
    if (data.success) {
      window.location.href = data.redirect;
    } else {
      flattyToast('error', data.message ?? 'toast.login.denied');
    }
  });
</script>