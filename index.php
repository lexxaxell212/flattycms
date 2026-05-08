<?php
$page_title = "Home - Ayokebandung.id";
require_once "includes/header.php";
?>
<style>
.main-content {
    padding-top: var(--navbar-height) !important;
}
</style>
<?php
function safe_include($file_path, $fallback_title = "Konten")
{
  if (!file_exists($file_path)) {
    echo fallback_card($fallback_title);
    return;
  }
  ob_start();
  try {
    include $file_path;
    ob_end_flush();
  } catch (Throwable $e) {
    ob_end_clean();
    echo fallback_card($fallback_title);
  }
}
function fallback_card($title = "Konten")
{
  return '
    <div class="container py-6">
      <div class="row mx-auto">
        <div class="col-12">
            <div class="card card-glass">
                <div class="card-body text-center py-5">
                    <i class="fas fa-circle-notch fa-spin fa-1x text-muted mb-3"></i>
                    <h5 class="text-muted mb-1">' .
    htmlspecialchars($title) .
    '</h5>
                    <p class="text-muted small mb-0">Sedang dalam perbaikan.</p>
                </div>
            </div>
        </div>
      </div>
    </div>';
}
safe_include("parts/hero.php", "Parts Hero Section");
safe_include("parts/kenapa-bandung.php", "Parts Kenapa Bandung");
safe_include("parts/blog-card.php", "Parts Artikel Terbaru");
safe_include("parts/update-card.php", "Parts Update Terkini");
?>
<script>
  let currentHeroIndex = 0;
const heroSlides = document.querySelectorAll('.hero-item');
const heroDots = document.querySelectorAll('.dot');
let heroInterval;

function showHeroSlide(index) {
    heroSlides.forEach(slide => slide.classList.remove('active'));
    heroDots.forEach(dot => dot.classList.remove('active'));
    heroSlides[index].classList.add('active');
    heroDots[index].classList.add('active');
    currentHeroIndex = index;
}
function nextHeroSlide() {
    let next = (currentHeroIndex + 1) % heroSlides.length;
    showHeroSlide(next);
}
function heroJump(index) {
    clearInterval(heroInterval);
    showHeroSlide(index);
    startHeroAutoSlide(); 
}
function startHeroAutoSlide() {
    heroInterval = setInterval(nextHeroSlide, 5000); 
}
document.addEventListener('DOMContentLoaded', () => {
    startHeroAutoSlide();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const containers = document.querySelectorAll('.kenapa-image-container');
  containers.forEach(container => {
    const slideContainer = container.querySelector('.kenapa-image-slide');
    const images = slideContainer.querySelectorAll('.kenapa-image');
    if (images.length < 2) return;
    let currentIndex = 0;
    const slideDuration = 5000;
    let slideInterval;
    function nextSlide() {
      currentIndex = (currentIndex + 1) % images.length;
      slideContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
    }
    function startAutoSlide() {
      slideInterval = setInterval(nextSlide, slideDuration);
    }
    function stopAutoSlide() {
      clearInterval(slideInterval);
    }
    container.addEventListener('mouseenter', stopAutoSlide);
    container.addEventListener('mouseleave', startAutoSlide);
    startAutoSlide();
  });
});
</script>
<script>
(function () {
    const track = document.getElementById('sliderTrack');
    const cards = track.querySelectorAll('.slide-card');
    const total = cards.length;
    let current = 0;
    let startX = 0;
    function getVisible() {
        return window.innerWidth >= 768 ? 2 : 1;
    }
    function maxIndex() {
        return Math.max(0, total - getVisible());
    }
    function updateSlider() {
        const cardWidth = cards[0].offsetWidth + 32;
        track.style.transform = `translateX(-${current * cardWidth}px)`;
        document.getElementById('btnPrev').disabled = current === 0;
        document.getElementById('btnNext').disabled = current >= maxIndex();
    }
    window.moveSlide = function (dir) {
        current = Math.min(Math.max(current + dir, 0), maxIndex());
        updateSlider();
    };
    track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; });
    track.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) moveSlide(diff > 0 ? 1 : -1);
    });
    window.addEventListener('resize', updateSlider);
    updateSlider();
})();
</script>
<?php require "includes/footer.php";
?>