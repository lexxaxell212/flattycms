<?php
$token = trim($_GET['token'] ?? '');
$invalid = true;

if ($token) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();
    if ($reset) $invalid = false;
}

$page_title = 'Reset Password — ' . SITE_NAME;
?>

<script src="<?= JS_URL ?>user-helper.js" defer></script>

<main id="content" class="container-fluid">
<div class="container">

<div class="card border-0 shadow-sm rounded-4 p-4" style="width:100%;max-width:400px;">
    <a href="/login" class="text-muted small text-decoration-none mb-3 d-inline-flex align-items-center gap-1">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali ke login
    </a>

    <?php if ($invalid): ?>
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

</div>
</main>

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
</script>