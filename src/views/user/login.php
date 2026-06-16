<?php
if (isset($_SESSION['user'])) {
  header('Location: /profile');
  exit;
}
?>
<main class="main-content">
  <div class="container">
    <form class="bg-surface mx-auto my-5 row g-2">
      <div class="col-12">
        <h1 class="h3">Selamat datang</h1>
        <p class="text-muted">
          Masuk ke akun kamu
        </p>
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-at me-1"></i>
          Email atau username
        </label>
        <input type="text" id="login-id" class="form-control" placeholder="nama@email.com">
      </div>
      <div class="col-12">
        <label class="form-label">
          <i class="fas fa-lock me-1"></i>
          Password
        </label>
        <div class="input-group">
          <input type="password" id="login-pw" class="form-control password" placeholder="••••••••">
          <button class="btn text-muted" type="button" id="toggle-pw">
            <i class="fas fa-eye fa-sm"></i>
          </button>
        </div>
      </div>
      <div class="col-12 py-4">
        <button class="btn btn-outline-primary" id="btn-login">
          <span id="btn-login-text">Masuk</span>
          <i id="btn-login-spinner" class="d-none fa-solid fa-circle-notch fa-spin ms-1"></i>
        </button>
      </div>
    </form>
    <div class="d-flex justify-content-center mb-4">
      <a href="/forgot-password" class="small">Lupa password?</a>
    </div>
    <div class="divider">
      atau
    </div>
    <div class="d-flex justify-content-center">
      <div id="google-login-page"></div>
    </div>
    <p class="text-center text-muted small mt-2">
      Belum punya akun? <a href="/register" class="fw-medium">Daftar sekarang</a>
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
    const btnText = document.getElementById('btn-login-text');
    const btnSpinner = document.getElementById('btn-login-spinner');

    if (!identifier || !password) {
      flattyToast('error', 'Email/username dan password wajib diisi.');
      return;
    }
    btn.disabled = true;
    btnText.textContent = 'Masuk';
    btnSpinner.classList.remove('d-none');
    await new Promise(r => setTimeout(r, 500));
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
    btnText.textContent = 'Masuk';
    btnSpinner.classList.add('d-none');
    if (data.success) {
      window.location.href = data.redirect;
    } else {
      flattyToast('error', data.message ?? 'Email/username atau password salah.');
    }
  });
</script>