// login
document.getElementById('toggle-pw').addEventListener('click', () => {
        const pw = document.getElementById('login-pw');
        const icon = document.querySelector('#toggle-pw i');
        pw.type = pw.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
});

document.getElementById('btn-login').addEventListener('click', async () => {
        const identifier = document.getElementById('login-id').value.trim();
        const password = document.getElementById('login-pw').value;
        const errorEl = document.getElementById('login-error');

        if (!identifier || !password) {
            errorEl.textContent = 'Email/username dan password wajib diisi.';
            errorEl.classList.remove('d-none');
            return;
        }

        const res = await fetch('/api/auth/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': CONFIG.csrfToken
        },
        body: JSON.stringify({ identifier, password })
        });

        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            errorEl.textContent = data.message ?? 'Email/username atau password salah.';
            errorEl.classList.remove('d-none');
        }
});

//register
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

//forgot password
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

//reset password
document.getElementById('btn-rp')?.addEventListener('click', async () => {
        const password  = document.getElementById('rp-pw').value;
        const confirm   = document.getElementById('rp-pw-confirm').value;
        const errorEl   = document.getElementById('rp-error');
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