</div>
<footer id="footer" class="footer mt-5">
  <div class="container">
    <section class="newsletter-section py-5">
      <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 text-center">
          <i class="fas fa-envelope mb-4" style="font-size:40px; color:
          var(--blue-900);"></i>
          <div id="registernewsletter" class="mb-4">
            <h2 class="fw-bold">NEWSLETTER</h2>
            <p>Dapatkan event dan update Bandung terkini via email.</p>
          </div>
          
          <div class="newsletter-container mx-auto" style="max-width: 500px;">
            <form class="newsletter-form d-flex gap-2" id="newsletterForm">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
              <input type="email" name="email" class="form-control email-input" id="emailInput" placeholder="user@gmail.com" required>
              <button type="submit" class="btn btn-primary px-4 btn-sm" id="submitBtn">
                Berlangganan
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <div class="logo-container text-center mt-5">
      <div class="logo-main mb-4 mx-auto"></div>
      <div class="logo-footer d-flex justify-content-center gap-3">
        <a class="logo-social text-muted" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a class="logo-social text-muted" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
        <a class="logo-social text-muted" href="#" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>
    <div class="footer-links text-center mt-4">
      <ul class="list-inline">
        <li class="list-inline-item"><a href="<?= PAGES_URL ?>tentang" class="text-decoration-none text-muted">Tentang</a></li>
        <li class="list-inline-item px-3 text-muted">|</li>
        <li class="list-inline-item"><a href="<?= PAGES_URL ?>privacy-policy" class="text-decoration-none text-muted">Privasi</a></li>
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
