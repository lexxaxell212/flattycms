<?php 
$slides = [
  ['slug' => 'wisata'],
  ['slug' => 'kuliner'],
  ['slug' => 'hotel']
];
?>

<style>
.main-content { padding-top: var(--navbar-height) !important; }
</style>

<script src="<?= JS_URL ?>hero.js" defer></script>

<section id="hero-website" class="hero-wrapper position-relative overflow-hidden">

  <?php foreach ($slides as $i => $s): ?>
  <div class="hero-item <?= $i === 0 ? 'active hero-slide-' . $s['slug'] : '' ?>"
       <?= $i > 0 ? 'data-slug="' . $s['slug'] . '"' : '' ?>></div>
  <?php endforeach; ?>

  <div class="hero-overlay"></div>
  <div class="hero-overlay-global"></div>

  <div class="container h-100 d-flex align-items-center hero-card-wrapper">
    <div class="glass-hero-card col-12 col-md-7 col-lg-5">
      <h1 class="mb-3" style="color:var(--dotid);">Jelajahi Bandung</h1>
      <p class="lead">Eksplorasi destinasi, kuliner, dan penginapan terbaik di Kota Kembang.</p>
      <div class="d-flex gap-2">
        <a href="/things-to-do" class="btn btn-outline-primary">
          Lihat Event
        </a>
        <a href="/trip" class="btn btn-primary">
          Mulai Rencanakan <i class="arrow-icon fas fa-arrow-right ms-2"></i>
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