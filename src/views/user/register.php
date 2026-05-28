<?php
$page_title = 'Daftar — ' . SITE_NAME;
?>

<script src="<?= JS_URL ?>user-helper.js" defer></script>

<main id="content">
<div class="container">
<section id="daftar-akun">
  <h2 class="text-center">Buat akun baru</h2>
  <p class="text-muted mb-5 text-center">Isi data di bawah untuk mendaftar</p>
<div class="card bg-light p-4 mx-auto" style="width:100%;max-width:440px;">
    <div id="register-error" class="alert alert-danger d-none small py-2"></div>
    <div id="register-success" class="alert alert-success d-none small py-2"></div>
    <div class="mb-3">
        <label class="form-label">Nama lengkap</label>
        <div class="input-group">
            <input type="text" id="reg-name" class="form-control"
            placeholder="Nama" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group mb-2">
            <input type="text" id="reg-username" class="form-control"
            placeholder="username" required>
        </div>
        <div class="small text-muted">Hanya huruf, angka, dan underscore.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <input type="email" id="reg-email" class="form-control"
            placeholder="nama@email.com" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <input type="password" id="reg-pw" class="form-control" placeholder="Min. 8 karakter">
            <button class="btn" type="button" id="toggle-pw">
                <i class="text-muted fa-regular fa-eye fa-sm"></i>
            </button>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label">Konfirmasi password</label>
        <div class="input-group">
            <input type="password" id="reg-pw-confirm" class="form-control" placeholder="Ulangi password">
            <button class="btn" type="button" id="toggle-pw-confirm">
                <i class="text-muted fa-regular fa-eye fa-sm"></i>
            </button>
        </div>
    </div>
    <button class="btn btn-outline-primary w-100 mb-3" id="btn-register">Daftar</button>
    <div class="text-center text-muted small mt-4">
        Sudah punya akun? <a href="/login" class="fw-medium">Masuk</a>
    </div>
</div>
</section>
</div>
</main>
<script>
    function togglePw(inputId, btnId) {
        document.getElementById(btnId).addEventListener('click', () => {
            const input = document.getElementById(inputId);
            const icon = document.querySelector(`#${btnId} i`);
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    togglePw('reg-pw', 'toggle-pw');
    togglePw('reg-pw-confirm', 'toggle-pw-confirm');
</script>