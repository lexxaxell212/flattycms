<?php
if (isset($_SESSION['user'])) {
    header('Location: /profile');
    exit;
    }
?>

<script src="<?= JS_URL ?>user-helper.js" defer></script>

<main id="content">
<div class="container">

<div>
  <section id="masuk-akun">
    <h2>Selamat datang</h2>
    <p class="text-muted">Masuk ke akun kamu</p>
  </section>
    
    <div id="login-error" class="alert alert-danger d-none small py-2"></div>
    <div class="mb-3">
        <label class="form-label">
          <svg
          xmlns="http://www.w3.org/2000/svg"
          width="1rem"
          height="1rem"
          viewBox="0 0 16 16"
          fill="none"
          stroke="var(--text-heading)"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <circle cx="8" cy="5.5" r="2.5"/>
          <path d="M2 14c0-3.314 2.686-5 6-5s6 1.686 6 5"/>
        </svg>
          Email atau username
        </label>
        <input type="text" id="login-id" class="form-control" placeholder="nama@email.com">
    </div>

    <div class="mb-4">
        <label class="form-label">
          <svg
          xmlns="http://www.w3.org/2000/svg"
          width="1rem"
          height="1rem"
          viewBox="0 0 16 16"
          fill="none"
          stroke="var(--text-heading)"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M5 7V5a3 3 0 0 1 6 0v2"/>
          <rect x="2.5" y="7" width="11" height="8" rx="1.5" ry="1.5"/>
          <circle cx="8" cy="11" r="1" fill="var(--text-heading)" stroke="none"/>
        </svg>
          Password
        </label>
        <div class="input-group">
            <input type="password" id="login-pw" class="form-control password" placeholder="••••••••">
            <button class="btn text-muted" type="button" id="toggle-pw">
                <i class="fa-regular fa-eye fa-sm"></i>
            </button>
        </div>
    </div>
    <button class="btn btn-outline-primary mb-5" id="btn-login">Masuk</button>
    
    <div class="d-flex justify-content-center mb-3">
        <a href="/forgot-password" class="small">Lupa password?</a>
    </div>
    <div class="divider">atau</div>
    <div class="d-flex justify-content-center">
    <div id="google-login-page"></div>
    </div>
    <p class="text-center text-muted small mt-3">
        Belum punya akun? <a href="/register" class="fw-medium">Daftar sekarang</a>
    </p>
</div>
</div>
</main>