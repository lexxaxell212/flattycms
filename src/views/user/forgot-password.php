<?php
$page_title = 'Lupa Password — ' . SITE_NAME;
?>

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

<script>
    document.getElementById('btn-fp').addEventListener('click', async () => {
        const email   = document.getElementById('fp-email').value.trim();
        const errorEl = document.getElementById('fp-error');
        const successEl = document.getElementById('fp-success');

        errorEl.classList.add('d-none');
        successEl.classList.add('d-none');

        if (!email) {
            errorEl.textContent = 'Email wajib diisi.';
            errorEl.classList.remove('d-none');
            return;
        }

        const btn = document.getElementById('btn-fp');
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        const res = await fetch('/api/auth/forgot-password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': CONFIG.csrfToken
            },
            body: JSON.stringify({ email })
        });

        const data = await res.json();
        if (data.success) {
            successEl.textContent = 'Link reset password sudah dikirim ke emailmu. Cek inbox atau folder spam.';
            successEl.classList.remove('d-none');
            document.getElementById('fp-email').value = '';
        } else {
            errorEl.textContent = data.message ?? 'Gagal mengirim email.';
            errorEl.classList.remove('d-none');
        }

        btn.disabled = false;
        btn.textContent = 'Kirim Link Reset';
    });
</script>