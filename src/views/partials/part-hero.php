<?php 
$slides = [
  ['slug' => 'wisata'],
  ['slug' => 'kuliner'],
  ['slug' => 'hotel']
];
?>
<script src="<?= JS_URL ?>hero.js" defer></script>
<header id="hero-website" class="hero-wrapper position-relative overflow-hidden">
  <?php foreach ($slides as $i => $s): ?>
  <div class="hero-item <?= $i === 0 ? 'active hero-slide-' . $s['slug'] : '' ?>"
       <?= $i > 0 ? 'data-slug="' . $s['slug'] . '"' : '' ?>></div>
  <?php endforeach; ?>
  <div class="hero-overlay"></div>
  <div class="hero-overlay-global"></div>
  <div class="container h-100 d-flex align-items-end hero-card-wrapper">
  <div class="hero-content col-12 col-md-8 col-lg-6">
    <span class="hero-eyebrow">Explore</span>
    <h1 class="hero-title">BANDUNG</h1>
    <p class="hero-lead">Mulai dari mana? Mau kemana? Ngapain aja?</p>
    <div class="d-flex flex-column align-items-start gap-3">
      <a href="/trip" class="btn btn-hero">
        TRIP PLANNER <i class="arrow-icon fas fa-angle-right ms-2"></i>
      </a>
      <a href="/things-to-do" class="btn btn-outline-white">
        THINGS TO DO <i class="arrow-icon fas fa-angle-right ms-2"></i>
      </a>
    </div>
  </div>
  </div>
  <div class="hero-dots">
    <?php foreach ($slides as $i => $s): ?>
      <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="heroJump(<?= $i ?>)"></span>
    <?php endforeach; ?>
  </div>
</header>
<main>