<footer id="footer">
  <div class="container">
    <div class="bg-surface border-0">
      <div class="text-center">
        <div class="mb-5">
          <h2 class="h3" data-bhs="f.newsletter.title">NEWSLETTER</h2>
          <p class="small" data-bhs="f.newsletter excerpt">
            Dapatkan event dan update Bandung terkini via email.
          </p>
        </div>
        <form id="newsletterForm" class="row mx-auto align-items-center g-3" novalidate>
          <div class="col-md-8">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <input type="email" name="email" class="form-control" id="emailInput" data-bhs="f.newsletter.placeholder" placeholder="nama@email.com" required>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary" id="submitBtn">
              <span data-bhs="btn.subs">Berlangganan</span>
              <i class="arrow-icon fas fa-paper-plane"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
    <section class="footer-nav">
      <div class="footer-nav__col">
        <span class="footer-nav__label" data-bhs="f.pages.title">Halaman</span>
        <ul>
          <li><a class="<?= isActive('/') ?>" href="<?= BASE_URL ?>" data-bhs="f.pages.1">Beranda</a></li>
          <li><a class="<?= isActive('/blogs') ?>" href="/blogs" data-bhs="f.pages.2">Blogs</a></li>
          <li><a class="<?= isActive('/pages/sejarah') ?>" href="/pages/sejarah" data-bhs="f.pages.3">Sejarah Bandung</a></li>
          <li><a class="<?= isActive('/pages/layanan') ?>" href="/pages/layanan" data-bhs="f.pages.4">Layanan di Bandung</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label" data-bhs="f.user.title">Untuk Pengunjung</span>
        <ul>
          <li><a class="<?= isActive('/things-to-do') ?>" href="/things-to-do" data-bhs="f.user.1">Aktifitas</a></li>
          <li><a class="<?= isActive('/trip') ?>" href="/trip" data-bhs="f.user.2">Perencana Perjalanan</a></li>
          <li><a class="<?= isActive('/gallery') ?>" href="/gallery" data-bhs="f.user.3">Galeri dan Ulasan</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label" data-bhs="f.info.title">Informasi</span>
        <ul>
          <li><a class="<?= isActive('/pages/tentang') ?>" href="/pages/tentang" data-bhs="f.info.1">Tentang</a></li>
          <li><a class="<?= isActive('/pages/privacy-policy') ?>" href="/pages/privacy-policy" data-bhs="f.info.2">Privasi</a></li>
          <li><a class="<?= isActive('/pages/kritik-dan-saran') ?>" href="/pages/kritik-dan-saran" data-bhs="f.info.3">Kritik dan Saran</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label" data-bhs="f.follow.title">Ikuti Kami</span>
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
<script>
  const links = document.querySelectorAll('.logo-social');
  links.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      flattyToast('warning', 'toast.link.unset');
    });
  });
</script>
</body>
</html>