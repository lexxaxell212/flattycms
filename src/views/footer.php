</div>
<footer id="footer">
  <div class="container">

    <section id="newsletter-ayokebandung.id" class="footer-newsletter">
      <div class="row justify-content-center">
        <div class="col text-center">
          <div class="mb-4">
            <h2 class="text-gradient">NEWSLETTER</h2>
            <span class="text-muted">Dapatkan event dan update Bandung terkini via email.</span>
          </div>
          <div class="mx-auto" style="max-width:420px">
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
        <span class="footer-nav__label">Jelajahi</span>
        <ul>
          <li><a href="<?= BASE_URL ?>">Beranda</a></li>
          <li><a href="<?= BASE_URL ?>pages/">Things to Do</a></li>
          <li><a href="<?= BASE_URL ?>blogs/">Blog</a></li>
          <li><a href="<?= BASE_URL ?>gallery/">Galeri</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label">Fitur</span>
        <ul>
          <li><a href="<?= BASE_URL ?>trip/">Trip Planner</a></li>
          <li><a href="<?= BASE_URL ?>gallery/#tab-review">Review</a></li>
          <li><a href="<?= BASE_URL ?>login/">Login</a></li>
        </ul>
      </div>
      <div class="footer-nav__col">
        <span class="footer-nav__label">Info</span>
        <ul>
          <li><a href="<?= PAGES_URL ?>tentang/">Tentang</a></li>
          <li><a href="<?= PAGES_URL ?>privacy-policy/">Privasi</a></li>
          <li><a href="<?= PAGES_URL ?>kontak/">Kontak</a></li>
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
      <div class="logo-main"></div>
      <p class="text-uppercase text-muted small mb-0" style="letter-spacing:2px;opacity:.9">
        <?= date('Y') ?> <?= SITE_NAME ?>
      </p>
    </div>

  </div>
</footer>

<style>
#footer {
  padding-top: 4rem;
  padding-bottom: 2rem;
}
.footer-newsletter {
  padding-bottom: 4rem;
}
.footer-nav {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
  padding: 3rem 0;
}
@media (max-width: 767px) {
  .footer-nav { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
}
@media (max-width: 400px) {
  .footer-nav { grid-template-columns: 1fr; }
}
.footer-nav__label {
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #7c3aed;
  display: block;
  margin-bottom: .875rem;
}
.footer-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: .5rem;
}
.footer-nav ul li a {
  font-size: .875rem;
  color: #6b7280;
  text-decoration: none;
  transition: color .2s;
}
.footer-nav ul li a:hover { color: #7c3aed; }
.footer-hr { border-color: #ede9fe; opacity: 1; margin: 0; }
.footer-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 1.5rem;
  flex-wrap: wrap;
  gap: 1rem;
}
@media (max-width: 575px) {
  .footer-bottom { flex-direction: column; align-items: center; text-align: center; }
}
</style>
</body>
</html>
