<section id="hero-website" class="hero-wrapper position-relative overflow-hidden">
  <?php 
  $slides = [
    ['title' => 'WISATA', 'desc' => 'Eksplorasi destinasi ikonik Bandung dengan layanan premium.', 'img' => 'wisata.webp'],
    ['title' => 'KULINER', 'desc' => 'Manjakan lidah dengan cita rasa autentik kelas dunia.', 'img' => 'kuliner.webp'],
    ['title' => 'HOTEL', 'desc' => 'Temukan kemewahan menginap terbaik di lokasi strategis.', 'img' => 'hotel.webp']
  ];
  foreach ($slides as $i => $s): 
  ?>
  <div class="hero-item <?= $i === 0 ? 'active' : '' ?>" 
       style="background-image: url('<?= IMG_URL . $s['img'] ?>')">
    <div class="hero-overlay"></div>
    <div class="container h-100 d-flex align-items-center">
      <div class="glass-hero-card col-12 col-md-7 col-lg-5">
        <h1 class="display-4 fw-bold mb-3 text-blue-800"><?= $s['title'] ?></h1>
        <p class="lead mb-4 text-secondary"><?= $s['desc'] ?></p>
        <div class="d-flex gap-3">
          <a href="#explore" class="btn btn-gradient-blue shadow-lg">
            Mulai Jelajahi <i class="fas fa-arrow-right ms-2"></i>
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
<style>
  :root {
    --mistyG-soft: #f8fafc; 
    --blueG-500: #3b82f6;
    --blueG-700: #1d4ed8;
    --blueG-800: #1e40af;
    --radius: 0.75rem;
}

.hero-wrapper {
    height: 85vh;
    background: var(--misty-soft);
}

.hero-item {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.8s ease;
    transform: scale(1.1); /* Ken Burns Effect */
}

.hero-item.active {
    opacity: 1;
    transform: scale(1);
    z-index: 1;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    /* Gradient tipis dari kiri ke kanan, bukan hitam pekat */
    background: linear-gradient(90deg, rgba(248, 250, 252, 0.9) 0%, rgba(248, 250, 252, 0.2) 100%);
}

/* Glass Card */
.glass-hero-card {
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 3rem;
    border-radius: var(--radius);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    animation: fadeInUp 0.8s ease forwards;
}

.btn-gradient-blue {
    background: linear-gradient(to right, var(--blue-500), var(--blue-700));
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius);
    font-weight: 600;
    transition: 0.3s;
}

.btn-gradient-blue:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.5);
}

/* Dark Mode Support */
[data-dark] .hero-overlay {
    background: linear-gradient(90deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.3) 100%);
}
[data-dark] .glass-hero-card {
    background: rgba(30, 41, 59, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
/* Animasi buat konten di dalam slide aktif */
.hero-item.active .glass-hero-card h1 {
    animation: fadeInUp 0.8s ease forwards 0.2s;
    opacity: 0;
}

.hero-item.active .glass-hero-card p {
    animation: fadeInUp 0.8s ease forwards 0.4s;
    opacity: 0;
}

.hero-item.active .glass-hero-card .btn {
    animation: fadeInUp 0.8s ease forwards 0.6s;
    opacity: 0;
}

/* Dots Navigation Style */
.hero-dots {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 10;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: 0.3s;
}

.dot.active {
    background: var(--blue-500);
    width: 30px; /* Efek memanjang ala modern UI */
    border-radius: 10px;
}

[data-dark] .dot {
    background: rgba(255, 255, 255, 0.3);
}

</style>