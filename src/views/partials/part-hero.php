<?php
$slides = [["slug" => "wisata"], ["slug" => "kuliner"], ["slug" => "hotel"]]; ?>
<script src="<?= JS_URL ?>hero.js" defer></script>
<div id="hero-website" class="hero-wrapper position-relative overflow-hidden">
  <?php foreach ($slides as $i => $s): ?>
  <div class="hero-item <?= $i === 0
    ? "active hero-slide-" . $s["slug"]
    : "" ?>"
       <?= $i > 0 ? 'data-slug="' . $s["slug"] . '"' : "" ?>></div>
  <?php endforeach; ?>
  <div class="hero-overlay"></div>
  <div class="hero-overlay-global"></div>
  <div class="hero-card-wrapper">
  <div class="container hero-content align-items-end">
    <div class="row g-4 hero-content-inner">
      <div class="col-12">
        <span class="hero-eyebrow" data-bhs="hero.eyebrow">Explore</span>
        <h1 class="hero-title" data-bhs="hero.title">BANDUNG</h1>
        <p class="hero-lead" data-bhs="hero.excerpt">Mulai dari mana? Mau kemana? Ngapain aja?</p>
      </div>
      <div class="col-12">
        <a href="/trip" class="btn btn-hero">
          <span data-bhs="btn.tp">TRIP PLANNER</span> <i class="arrow-icon fas fa-angle-right ms-2"></i>
        </a>
        <a href="/things-to-do" class="btn btn-outline-white ms-2">
          <span data-bhs="btn.ttd">THINGS TO DO</span> <i class="arrow-icon fas fa-angle-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>
  </div>
  <div class="hero-dots">
    <?php foreach ($slides as $i => $s): ?>
      <span class="dot <?= $i === 0
        ? "active"
        : "" ?>" onclick="heroJump(<?= $i ?>)"></span>
    <?php endforeach; ?>
  </div>
</div>
<main class="main-content">