<?php

if (!empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}
$page_title = 'Daftar — ' . SITE_NAME;
?>
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
<script>
    const CONFIG = { csrfToken: '<?= generate_csrf_token() ?>' };

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

    document.getElementById('btn-register').addEventListener('click', async () => {
        const name     = document.getElementById('reg-name').value.trim();
        const username = document.getElementById('reg-username').value.trim();
        const email    = document.getElementById('reg-email').value.trim();
        const password = document.getElementById('reg-pw').value;
        const confirm  = document.getElementById('reg-pw-confirm').value;
        const errorEl  = document.getElementById('register-error');
        const successEl = document.getElementById('register-success');

        errorEl.classList.add('d-none');
        successEl.classList.add('d-none');

        if (!name || !username || !email || !password || !confirm) {
            errorEl.textContent = 'Semua field wajib diisi.';
            errorEl.classList.remove('d-none');
            return;
        }
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            errorEl.textContent = 'Username hanya boleh huruf, angka, dan underscore.';
            errorEl.classList.remove('d-none');
            return;
        }
        if (password.length < 8) {
            errorEl.textContent = 'Password minimal 8 karakter.';
            errorEl.classList.remove('d-none');
            return;
        }
        if (password !== confirm) {
            errorEl.textContent = 'Konfirmasi password tidak cocok.';
            errorEl.classList.remove('d-none');
            return;
        }

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
        if (data.success) {
            successEl.textContent = 'Akun berhasil dibuat! Mengalihkan...';
            successEl.classList.remove('d-none');
            setTimeout(() => window.location.href = data.redirect, 1500);
        } else {
            errorEl.textContent = data.message ?? 'Pendaftaran gagal.';
            errorEl.classList.remove('d-none');
        }
    });
</script>