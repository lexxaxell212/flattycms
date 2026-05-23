<?php 
$slides = [
  ['title' => 'WISATA',  'desc' => 'Eksplorasi destinasi ikonik Bandung dengan layanan premium.', 'slug' => 'wisata'],
  ['title' => 'KULINER', 'desc' => 'Manjakan lidah dengan cita rasa autentik kelas dunia.',       'slug' => 'kuliner'],
  ['title' => 'HOTEL',   'desc' => 'Temukan kemewahan menginap terbaik di lokasi strategis.',     'slug' => 'hotel']
];
?>

<style>
.main-content { padding-top: var(--navbar-height) !important; }
</style>
<script src="<?= JS_URL ?>hero.js" defer></script>

<section id="hero-website" class="hero-wrapper position-relative overflow-hidden">

  <?php foreach ($slides as $i => $s): ?>
  <div class="hero-item <?= $i === 0 ? 'active hero-slide-' . $s['slug'] : '' ?>"
       <?= $i > 0 ? 'data-slug="' . $s['slug'] . '"' : '' ?>
       data-title="<?= $s['title'] ?>"
       data-desc="<?= $s['desc'] ?>">
    <div class="hero-overlay"></div>
  </div>
  <?php endforeach; ?>

  <div class="hero-overlay-global"></div>

  <div class="container h-100 d-flex align-items-center hero-card-wrapper">
    <div class="glass-hero-card col-12 col-md-7 col-lg-5">
      <h1 class="mb-3 text-title hero-card-title"><?= $slides[0]['title'] ?></h1>
      <p class="lead hero-card-desc"><?= $slides[0]['desc'] ?></p>
      <div class="d-flex gap-3">
        <a href="<?= BASE_URL ?>trip-planner/" class="btn btn-primary">
          Mulai Rencanakan <i class="arrow-icon fas fa-arrow-right ms-2"></i>
        </a>
        <a href="<?= BASE_URL ?>pages/" class="btn btn-outline-light">
          Lihat Event
        </a>
      </div>
    </div>
  </div>

  <div class="hero-dots">
    <?php foreach ($slides as $i => $s): ?>
      <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="heroJump(<?= $i ?>)"></span>
    <?php endforeach; ?>
  </div>

</section>