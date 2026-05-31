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
      <span class="text-heading"
      style="font-weight:var(--fw-extrabold);font-size: clamp(1.4rem, 2.5vw,
      1.8rem);opacity:0.85">
        Explore
      </span>
      <br>
      <h1 class="text-hero"><em>BANDUNG</em></h1>
      <p class="lead">Mulai dari mana? Mau kemana? Ngapain aja?</p>
      <div class="d-flex flex-column align-items-start gap-2">
        <a href="/trip" class="btn btn-primary mb-2">
          TRIP PLANNER <i class="arrow-icon fas fa-angle-right ms-2"></i>
        </a>
        <a href="/things-to-do" class="btn btn-outline-primary" style="padding-block: calc(var(--space-3) - 2px)">
          THINGS TO DO
          <i class="arrow-icon fas fa-angle-right ms-2"></i>
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