</div> <footer id="footer" class="footer mt-5">
  <div class="container">
    <section class="newsletter-section py-5">
      <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 text-center">
          <i class="fas fa-envelope mb-4" style="font-size:40px; color: var(--primary-color);"></i>
          <div id="registernewsletter" class="mb-4">
            <h2 class="fw-bold">NEWSLETTER</h2>
            <p>Dapatkan event dan update Bandung terkini via email.</p>
          </div>
          
          <div class="newsletter-container mx-auto" style="max-width: 500px;">
            <form class="newsletter-form d-flex gap-2" id="newsletterForm">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
              <input type="email" name="email" class="form-control email-input" id="emailInput" placeholder="user@gmail.com" required>
              <button type="submit" class="btn btn-primary px-4" id="submitBtn">
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
        <a class="logo-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a class="logo-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
        <a class="logo-social" href="#" aria-label="Youtube"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>

    <div class="footer-links text-center mt-4">
      <ul class="list-inline">
        <li class="list-inline-item"><a href="<?= PAGES_URL ?>tentang" class="text-decoration-none text-muted">Tentang</a></li>
        <li class="list-inline-item px-3">|</li>
        <li class="list-inline-item"><a href="<?= PAGES_URL ?>privacy-policy" class="text-decoration-none text-muted">Privasi</a></li>
      </ul>
    </div>

    <div class="text-center mt-5 pb-3">
      <p class="text-uppercase ls-wide" style="font-size:10px; opacity:0.5; letter-spacing: 2px;">
        &copy; <?= date('Y') ?> AYOKEBANDUNG.ID
      </p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allowed = ['gmail.com', 'googlemail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'proton.me'];
    const form = document.getElementById('newsletterForm');
    const input = document.getElementById('emailInput');
    const btn = document.getElementById('submitBtn');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = input.value.trim();
        const domain = email.split('@')[1];

        if (!allowed.includes(domain)) {
            alert('Gunakan domain email umum (Gmail/Yahoo/Outlook)!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const formData = new FormData(form);
        formData.append('action', 'newsletter');

        fetch('<?= BASE_URL ?>newsletter-ajax.php', {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) input.value = '';
        })
        .catch(() => alert('Gangguan koneksi!'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        });
    });
});
</script>

<script src="<?= JS_URL ?>chat.js" defer></script>
<script src="<?= JS_URL ?>live-search.js" defer></script>
<script src="<?= JS_URL ?>navbar.js" defer></script>
<script src="<?= JS_URL ?>weather.js" defer></script>

</body>
</html>
