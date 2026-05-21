<style>
.btn-login { background: #222; color: #fff; border-radius: 10px; width: 100%; padding: 11px; font-size: 14px; border: none; }
.btn-login:hover { background: #444; color: #fff; }
.divider { display: flex; align-items: center; gap: 10px; color: #bbb; font-size: 12px; margin: 1.2rem 0; }
.divider::before, .divider::after { content: ''; flex: 1; height: 0.5px; background: #e0e0e0; }
</style>
<div class="card shadow-sm">
    <h5 class="fw-semibold mb-1">Selamat datang</h5>
    <p class="text-muted small mb-4">Masuk ke akun kamu</p>

    <div id="login-error" class="alert alert-danger d-none small py-2"></div>

    <div class="mb-3">
        <label class="form-label small text-muted">Email atau username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-regular fa-user fa-sm"></i></span>
            <input type="text" id="login-id" class="form-control" placeholder="contoh@email.com">
        </div>
    </div>

    <div class="mb-1">
        <label class="form-label small text-muted">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock fa-sm"></i></span>
            <input type="password" id="login-pw" class="form-control" placeholder="••••••••">
            <button class="btn btn-outline-secondary border-start-0" type="button" id="toggle-pw">
                <i class="fa-regular fa-eye fa-sm"></i>
            </button>
        </div>
    </div>

    <div class="text-end mb-3">
        <a href="/forgot-password" class="small text-primary text-decoration-none">Lupa password?</a>
    </div>

    <button class="btn btn-login" id="btn-login">Masuk</button>

    <div class="divider">atau</div>

    <div class="d-flex justify-content-center">
    <script>
          function handleGSI(response) {
          fetch('/api/auth/gsi.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-Token': CONFIG.csrfToken
              },
              body: JSON.stringify({ token: response.credential })
          })
          .then(r => r.json())
          .then(data => {
              if (data.success) window.location.href = data.redirect;
          });
          }
    </script>
    <script src="https://accounts.google.com/gsi/client" async></script>
      <div id="g_id_onload"
          data-client_id="353704633244-8jts0jtja4qlq58vd3b926h60j5psaka.apps.googleusercontent.com"
          data-callback="handleGSI"
          data-auto_select="false"
          data-cancel_on_tap_outside="true">
      </div>
      <div class="g_id_signin" data-type="standard" data-shape="pill"></div>
    </div>

    <p class="text-center text-muted small mt-3">
        Belum punya akun? <a href="/register" class="text-primary fw-medium text-decoration-none">Daftar sekarang</a>
    </p>
</div>

<script>
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
</script>