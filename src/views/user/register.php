<?php
$page_title = 'Daftar - ' . SITE_NAME;
?>
<main id="content">
<div class="container">
<section id="daftar-akun">
  <h2>Buat akun baru</h2>
  <p class="text-muted">Isi data di bawah untuk mendaftar</p>
</section>
<div class="mb-4">
    <label class="form-label mb-2">
      <i class="fas fa-circle-user me-1"></i>
      Nama lengkap
    </label>
    <div class="input-group">
        <input type="text" id="reg-name" class="form-control" placeholder="Nama" required>
    </div>
</div>
<div class="mb-4">
  <label class="form-label mb-2">
    <i class="fas fa-circle-user me-1"></i>
    Username
    </label>
  <div class="input-group mb-1">
    <input type="text" id="reg-username" class="form-control" placeholder="username" required>
    </div>
    <div class="form-text text-muted">Hanya huruf, angka, dan underscore.
    </div>
</div>
<div class="mb-4">
  <label class="form-label mb-2">
    <i class="fas fa-at me-1"></i>
    Email</label>
  <div class="input-group">
    <input type="email" id="reg-email" class="form-control" placeholder="nama@email.com" required>
  </div>
</div>
<div class="mb-4">
  <label class="form-label mb-2">
    <i class="fas fa-lock me-1"></i>
    Password
    </label>
  <div class="input-group">
    <input type="password" id="reg-pw-input" class="form-control" placeholder="Min. 8 karakter">
    <button class="btn" type="button" id="toggle-pw-reg">
      <i class="text-muted fas fa-eye fa-sm"></i>
    </button>
  </div>
</div>
<div class="mb-4">
  <label class="form-label mb-2">
    <i class="fas fa-lock me-1"></i>
    Konfirmasi password
    </label>
  <div class="input-group">
    <input type="password" id="reg-pw-confirm" class="form-control" placeholder="Ulangi password">
    <button class="btn" type="button" id="toggle-pw-confirm">
        <i class="text-muted fas fa-eye fa-sm"></i>
    </button>
  </div>
</div>
<button class="btn btn-outline-primary mb-4" id="btn-register">
  <span id="btn-register-text">Daftar</span>
  <i id="btn-register-spinner" class="d-none fa-solid fa-circle-notch fa-spin ms-1"></i>
</button>
<div class="text-center text-muted small mt-2">
  Sudah punya akun? <a href="/login" class="fw-medium">Masuk</a>
</div>
</div>
</main>
<script>
function togglePwReg(inputId, btnId) {
    document.getElementById(btnId).addEventListener('click', () => {
        const input = document.getElementById(inputId);
        const icon  = document.querySelector(`#${btnId} i`);
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
}
togglePwReg('reg-pw-input', 'toggle-pw-reg');
togglePwReg('reg-pw-confirm', 'toggle-pw-confirm');

document.getElementById('btn-register').addEventListener('click', async () => {
    const name       = document.getElementById('reg-name').value.trim();
    const username   = document.getElementById('reg-username').value.trim();
    const email      = document.getElementById('reg-email').value.trim();
    const password   = document.getElementById('reg-pw-input').value;
    const confirm    = document.getElementById('reg-pw-confirm').value;
    const btn        = document.getElementById('btn-register');
    const btnText    = document.getElementById('btn-register-text');
    const btnSpinner = document.getElementById('btn-register-spinner');

    if (!name || !username || !email || !password || !confirm) {
        flattyToast('error', 'Semua field wajib diisi.');
        return;
    }
    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        flattyToast('error', 'Username hanya boleh huruf, angka, dan underscore.');
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
    btnText.textContent = 'Mendaftar...';
    btnSpinner.classList.remove('d-none');
    const res = await fetch('/api/auth/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': CONFIG.csrfToken
        },
        body: JSON.stringify({ name, username, email, password })
    });
    const data = await res.json();
    btn.disabled = false;
    btnText.textContent = 'Daftar';
    btnSpinner.classList.add('d-none');
    if (data.success) {
        flattyToast('success', 'Akun berhasil dibuat! Cek emailmu untuk verifikasi sebelum login.');
    } else {
        flattyToast('error', data.message ?? 'Pendaftaran gagal.');
    }
});
</script>