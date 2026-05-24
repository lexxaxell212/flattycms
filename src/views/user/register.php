<?php
if (!empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}
$page_title = 'Daftar — ' . SITE_NAME;
?>

<script src="<?= JS_URL ?>user-helper.js" defer></script>

<main id="content" class="container-fluid">
<div class="container">

<div class="card border-0 shadow-sm rounded-4 p-4" style="width:100%;max-width:420px;">
    <h5 class="fw-semibold mb-1">Buat akun baru</h5>
    <p class="text-muted small mb-4">Isi data di bawah untuk mendaftar</p>

    <div id="register-error" class="alert alert-danger d-none small py-2"></div>
    <div id="register-success" class="alert alert-success d-none small py-2"></div>

    <div class="mb-3">
        <label class="form-label small text-muted">Nama lengkap</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted">
                <i class="fa-regular fa-user fa-sm"></i>
            </span>
            <input type="text" id="reg-name" class="form-control border-start-0" placeholder="John Doe">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small text-muted">Username</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted">
                <i class="fa-solid fa-at fa-sm"></i>
            </span>
            <input type="text" id="reg-username" class="form-control border-start-0" placeholder="johndoe">
        </div>
        <div class="form-text">Hanya huruf, angka, dan underscore.</div>
    </div>

    <div class="mb-3">
        <label class="form-label small text-muted">Email</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted">
                <i class="fa-regular fa-envelope fa-sm"></i>
            </span>
            <input type="email" id="reg-email" class="form-control border-start-0" placeholder="contoh@email.com">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label small text-muted">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted">
                <i class="fa-solid fa-lock fa-sm"></i>
            </span>
            <input type="password" id="reg-pw" class="form-control border-start-0" placeholder="Min. 8 karakter">
            <button class="btn btn-outline-secondary border-start-0" type="button" id="toggle-pw">
                <i class="fa-regular fa-eye fa-sm"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label small text-muted">Konfirmasi password</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted">
                <i class="fa-solid fa-lock fa-sm"></i>
            </span>
            <input type="password" id="reg-pw-confirm" class="form-control border-start-0" placeholder="Ulangi password">
            <button class="btn btn-outline-secondary border-start-0" type="button" id="toggle-pw-confirm">
                <i class="fa-regular fa-eye fa-sm"></i>
            </button>
        </div>
    </div>

    <button class="btn btn-dark w-100 rounded-3 py-2" id="btn-register">Daftar</button>

    <div class="text-center text-muted small mt-3">
        Sudah punya akun? <a href="/login" class="text-primary fw-medium text-decoration-none">Masuk</a>
    </div>
</div>

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