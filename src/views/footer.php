</div>
<footer id="footer" class="mt-5">
  <div class="container">
    <section class="py-5">
      <div class="row justify-content-center">
        <div class="col text-center">
          <div id="registernewsletter" class="mb-4">
            <h2 class="text-gradient">NEWSLETTER</h2>
            <p class="text-muted">Dapatkan event dan update Bandung terkini via email.</p>
          </div>
          <div class="mx-auto">
            <form class="newsletter-form" id="newsletterForm">
              <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
              <input type="email" name="email" class="form-control email-input"
              id="emailInput" placeholder="nama@email.com" required>
              <button type="submit" class="btn btn-primary" id="submitBtn">
                Berlangganan
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <div class="">
      <div class="logo-main mx-auto"></div>
      <div class="d-flex justify-content-center gap-3">
        <a class="logo-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a class="logo-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
        <a class="logo-social" href="#" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>
    <div class="text-center mt-4">
      <ul class="list-inline">
        <li class="list-inline-item"><a class="link" href="<?= PAGES_URL ?>tentang">Tentang</a></li>
        <li class="list-inline-item px-3 text-muted">|</li>
        <li class="list-inline-item"><a class="link" href="<?= PAGES_URL ?>privacy-policy">Privasi</a></li>
      </ul>
    </div>
    <div class="text-center mt-5 pb-3">
      <p class="text-uppercase ls-wide" style="font-size:10px; opacity:0.5; letter-spacing: 2px;">
        <?= date('Y') ?> <?= SITE_NAME ?>
      </p>
    </div>
  </div>
</footer>
</body>
</html>