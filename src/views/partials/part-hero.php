<style>
.main-content { padding-top: var(--navbar-height) !important; }
</style>
<script src="<?= JS_URL ?>hero.js" defer></script>
<section id="hero-website" class="hero-wrapper position-relative overflow-hidden">
  <?php 
  $slides = [
    ['title' => 'WISATA',  'desc' => 'Eksplorasi destinasi ikonik Bandung dengan layanan premium.', 'slug' => 'wisata'],
    ['title' => 'KULINER', 'desc' => 'Manjakan lidah dengan cita rasa autentik kelas dunia.',       'slug' => 'kuliner'],
    ['title' => 'HOTEL',   'desc' => 'Temukan kemewahan menginap terbaik di lokasi strategis.',     'slug' => 'hotel']
  ];
  foreach ($slides as $i => $s): 
  ?>
  <div class="hero-item <?= $i === 0 ? 'active hero-slide-' . $s['slug'] : '' ?>"
       <?php if ($i > 0): ?>
         data-slug="<?= $s['slug'] ?>"
       <?php endif; ?>>
    <div class="hero-overlay"></div>
    <div class="container h-100 d-flex align-items-center">
      <div class="glass-hero-card col-12 col-md-7 col-lg-5">
        <h1 class="display-4 fw-bold mb-3 text-blue-800"><?= $s['title'] ?></h1>
        <p class="lead mb-4 text-secondary"><?= $s['desc'] ?></p>
        <div class="d-flex gap-3">
          <a href="#kenapa-harus-bandung" class="btn btn-primary shadow-lg">
            Mulai Jelajahi <i class="arrow-icon fas fa-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="hero-dots">
    <?php foreach ($slides as $i => $s): ?>
      <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="heroJump(<?= $i ?>)"></span>
    <?php endforeach; ?>
  </div>
</section>