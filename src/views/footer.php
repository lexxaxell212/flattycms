</div>
<footer id="footer" class="container-fluid">
  <div class="container">
    <section id="newsletter-ayokebandung.id" class="footer-newsletter">
      <div class="row justify-content-center">
        <div class="col text-center">
          <div class="mb-4">
            <h2 class="text-gradient">NEWSLETTER</h2>
            <span class="text-muted">Dapatkan event dan update Bandung terkini via email.</span>
          </div>
          <div class="mx-auto" style="max-width:440px">
            <form class="newsletter-form" id="newsletterForm">
              <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
              <input type="email" name="email" class="form-control newsletter mb-3"
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
    <div class="footer-nav">
      <div class="footer-nav__col">
        <span class="footer-nav__label">Halaman</span>
        <ul>
          <li><a href="<?= BASE_URL ?>">Beranda</a></li>
          <li><a href="/blogs">Blogs</a></li>
          <li><a href="/pages/sejarah">Sejarah Bandung</a></li>
          <li><a href="/pages/layanan">Layanan di Bandung</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label">Untuk Pengunjung</span>
        <ul>
          <li><a href="/things-to-do">Things to Do</a></li>
          <li><a href="/trip">Trip Planner</a></li>
          <li><a href="/gallery">Gallery dan Review</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label">Info</span>
        <ul>
          <li><a href="/pages/tentang">Tentang</a></li>
          <li><a href="/pages/privacy-policy">Privasi</a></li>
          <li><a href="/pages/kritik-dan-saran">Kritik dan Saran</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label">Ikuti Kami</span>
        <div class="d-flex gap-3 mt-1">
          <a class="logo-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a class="logo-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a class="logo-social" href="#" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <hr class="footer-hr">
    <div class="footer-bottom">
      <p class="text-uppercase text-muted small mt-4"
      style="letter-spacing:2px;opacity:.9;">
        <?= date('Y') ?> <?= SITE_NAME ?>
      </p>
    </div>
  </div>
</footer>
</body>
</html>