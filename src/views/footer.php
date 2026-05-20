</div>
<footer id="footer">
  <div class="container">
    <section id="newsletter-ayokebandung.id">
      <div class="row justify-content-center">
        <div class="col text-center newsletter-section">
          <div id="registernewsletter" class="mb-5">
            <h2 class="text-gradient">NEWSLETTER</h2>
            <span class="text-muted">Dapatkan event dan update Bandung terkini
            via email.</span>
          </div>
          <div class="mx-auto">
            <form class="newsletter-form" id="newsletterForm">
              <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
              <input type="email" name="email" class="form-control newsletter
              mb-3"
              id="emailInput" placeholder="nama@email.com" required>
              <button type="submit" class="btn btn-primary" id="submitBtn">
                Berlangganan
                <i class="arrow-icon fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <div class="mb-5">
      <div class="logo-main mx-auto"></div>
      <p class="text-muted text-center">Ikuti kami :</p>
      <div class="d-flex justify-content-center gap-4">
        <a class="logo-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a class="logo-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
        <a class="logo-social" href="#" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>
    <div class="text-center py-2">
      <ul class="list-inline ">
        <li class="list-inline-item"><a href="<?= PAGES_URL ?>tentang">Tentang</a></li>
        <li class="list-inline-item text-muted"> - </li>
        <li class="list-inline-item"><a href="<?= PAGES_URL ?>privacy-policy">Privasi</a></li>
      </ul>
    </div>
    <hr>
    <div class="text-center">
      <p class="text-uppercase text-muted ls-wide small py-3"
      style="opacity:0.9;letter-spacing:2px;">
        <?= date('Y') ?> <?= SITE_NAME ?>
      </p>
    </div>
  </div>
</footer>
</body>
</html>