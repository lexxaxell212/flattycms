<?php
$page_title = 'Lupa Password — ' . SITE_NAME;
?>

<script src="<?= JS_URL ?>user-helper.js" defer></script>

<main id="content">
<div class="container">

<div>
  <section id="lupa-password">
    <a href="/login" class="text-muted small text-decoration-none mb-3 d-inline-flex align-items-center gap-1">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali ke login
    </a>

    <h2>Lupa password?</h2>
    <p class="text-muted small">Masukkan emailmu dan kami akan kirim link reset password.</p>
  </section>

    <div id="fp-error" class="alert alert-danger d-none small py-2"></div>
    <div id="fp-success" class="alert alert-success d-none small py-2"></div>

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
            <rect x="1" y="3" width="14" height="10" rx="1.5" ry="1.5"/>
            <polyline points="1,3 8,9.5 15,3"/>
          </svg>
          Email
        </label>
        <input type="email" id="fp-email" class="form-control" placeholder="nama@email.com">
    </div>

    <button class="btn btn-outline-primary" id="btn-fp">Kirim Link Reset</button>
</div>

</div>
</main>