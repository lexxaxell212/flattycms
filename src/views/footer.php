</div>
<footer id="footer">
  <div class="container">
    <section class="d-flex justify-content-center">
      <div class="flex-column text-center">
        <div class="mb-4">
            <h3>NEWSLETTER</h3>
            <p class="small">Dapatkan event dan update Bandung terkini via email.</p>
        </div>
        <form class="newsletter-form" id="newsletterForm">
              <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
              <input type="email" name="email" class="form-control mb-4" id="emailInput" placeholder="nama@email.com" required>
              <button type="submit" class="btn btn-primary" id="submitBtn">
                Berlangganan
                <i class="arrow-icon fas fa-paper-plane"></i>
              </button>
          </form>
        </div>
    </section>
    <section class="footer-nav">
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
    </section>
    <div class="footer-bottom">
      <span class="text-uppercase text-muted small" style="letter-spacing:2px;opacity:.9;">
        <?= date('Y') ?> <?= SITE_NAME ?>
      </span>
    </div>
  </div>
</footer>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const loader = document.getElementById('page-loader');
      document.body.style.visibility = 'visible';
      loader.style.width = '100%';
      setTimeout(() => {
        loader.style.opacity = '0';
        setTimeout(() => loader.remove(), 300);
      }, 700);
    });
  </script>
</body>
</html>