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
      <span class="text-primary" style="font-weight:var(--fw-extrabold);font-size: clamp(1.4rem, 2.5vw, 1.8rem);">
        JELAJAHI
      </span>
      <br>
      <h1 class="text-hero"><em>Bandung</em></h1>
      <p class="lead">Eksplorasi destinasi, kuliner, dan penginapan terbaik di Kota Kembang.</p>
      <div class="d-flex flex-column align-items-start gap-2">
        <a href="/trip" class="btn btn-primary">
          Mulai Rencanakan <i class="arrow-icon fas fa-arrow-right ms-2"></i>
        </a>
        <a href="/things-to-do" class="btn btn-outline-primary" style="padding-block: calc(var(--space-3) - 2px)">
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