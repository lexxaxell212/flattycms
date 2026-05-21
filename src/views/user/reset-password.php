<?php

if (!empty($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$token = trim($_GET['token'] ?? '');
if (!$token) {
    header('Location: /forgot-password');
    exit;
}

// validasi token
$pdo  = $GLOBALS['pdo'];
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    $invalid = true;
}

$page_title = 'Reset Password — ' . SITE_NAME;
?>

<div class="card border-0 shadow-sm rounded-4 p-4" style="width:100%;max-width:400px;">
    <a href="/login" class="text-muted small text-decoration-none mb-3 d-inline-flex align-items-center gap-1">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali ke login
    </a>

    <?php if (!empty($invalid)): ?>
        <div class="text-center py-3">
            <i class="fa-solid fa-circle-xmark fa-3x text-danger mb-3"></i>
            <h5 class="fw-semibold">Link tidak valid</h5>
            <p class="text-muted small">Link reset password sudah kadaluarsa atau tidak valid.</p>
            <a href="/forgot-password" class="btn btn-dark rounded-3 w-100 py-2">Minta link baru</a>
        </div>
    <?php else: ?>
        <h5 class="fw-semibold mb-1">Buat password baru</h5>
        <p class="text-muted small mb-4">Masukkan password baru untuk akunmu.</p>

        <div id="rp-error" class="alert alert-danger d-none small py-2"></div>
        <div id="rp-success" class="alert alert-success d-none small py-2"></div>

        <div class="mb-3">
            <label class="form-label small text-muted">Password baru</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-muted">
                    <i class="fa-solid fa-lock fa-sm"></i>
                </span>
                <input type="password" id="rp-pw" class="form-control border-start-0" placeholder="Min. 8 karakter">
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
                <input type="password" id="rp-pw-confirm" class="form-control border-start-0" placeholder="Ulangi password">
                <button class="btn btn-outline-secondary border-start-0" type="button" id="toggle-pw-confirm">
                    <i class="fa-regular fa-eye fa-sm"></i>
                </button>
            </div>
        </div>

        <button class="btn btn-dark w-100 rounded-3 py-2" id="btn-rp">Simpan Password</button>
    <?php endif; ?>
</div>

<script>
    function togglePw(inputId, btnId) {
        document.getElementById(btnId)?.addEventListener('click', () => {
            const input = document.getElementById(inputId);
            const icon  = document.querySelector('#' + btnId + ' i');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    togglePw('rp-pw', 'toggle-pw');
    togglePw('rp-pw-confirm', 'toggle-pw-confirm');

    document.getElementById('btn-rp')?.addEventListener('click', async () => {
        const password = document.getElementById('rp-pw').value;
        const confirm  = document.getElementById('rp-pw-confirm').value;
        const errorEl  = document.getElementById('rp-error');
        const successEl = document.getElementById('rp-success');

        errorEl.classList.add('d-none');
        successEl.classList.add('d-none');

        if (!password || !confirm) {
            errorEl.textContent = 'Semua field wajib diisi.';
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

        const btn = document.getElementById('btn-rp');
        btn.disabled    = true;
        btn.textContent = 'Menyimpan...';

        const res = await fetch('/api/auth/reset-password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': CONFIG.csrfToken
            },
            body: JSON.stringify({ token: '<?= safe_html($token) ?>', password })
        });

        const data = await res.json();
        if (data.success) {
            successEl.textContent = 'Password berhasil diubah! Mengalihkan ke halaman login...';
            successEl.classList.remove('d-none');
            btn.classList.add('d-none');
            setTimeout(() => window.location.href = '/login', 2000);
        } else {
            errorEl.textContent = data.message ?? 'Gagal mengubah password.';
            errorEl.classList.remove('d-none');
            btn.disabled    = false;
            btn.textContent = 'Simpan Password';
        }
    });
</script>