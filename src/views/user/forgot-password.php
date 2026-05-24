<?php
$page_title = 'Lupa Password — ' . SITE_NAME;
?>

<script src="<?= JS_URL ?>user-helper.js" defer></script>

<main id="content" class="container-fluid">
<div class="container">

<div class="card border-0 shadow-sm rounded-4 p-4" style="width:100%;max-width:400px;">
    <a href="/login" class="text-muted small text-decoration-none mb-3 d-inline-flex align-items-center gap-1">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali ke login
    </a>

    <h5 class="fw-semibold mb-1">Lupa password?</h5>
    <p class="text-muted small mb-4">Masukkan emailmu dan kami akan kirim link reset password.</p>

    <div id="fp-error" class="alert alert-danger d-none small py-2"></div>
    <div id="fp-success" class="alert alert-success d-none small py-2"></div>

    <div class="mb-4">
        <label class="form-label small text-muted">Email</label>
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 text-muted">
                <i class="fa-regular fa-envelope fa-sm"></i>
            </span>
            <input type="email" id="fp-email" class="form-control border-start-0" placeholder="contoh@email.com">
        </div>
    </div>

    <button class="btn btn-dark w-100 rounded-3 py-2" id="btn-fp">Kirim Link Reset</button>
</div>

</div>
</main>