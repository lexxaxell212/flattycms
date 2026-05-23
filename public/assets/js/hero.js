document.addEventListener('DOMContentLoaded', () => {
  const heroSlides = document.querySelectorAll('.hero-item');
  const heroDots   = document.querySelectorAll('.dot');

  if (!heroSlides.length) return;

  let currentIndex = 0;
  let heroInterval;

  function loadBg(el) {
    const slug = el.dataset.slug;
    if (!slug) return;
    el.classList.add(`hero-slide-${slug}`);
    delete el.dataset.slug;
  }

  function showSlide(index) {
    heroSlides.forEach(el => el.classList.remove('active'));
    heroDots.forEach(el => el.classList.remove('active'));
    heroSlides[index].classList.add('active');
    heroDots[index].classList.add('active');
    loadBg(heroSlides[index]);
    currentIndex = index;
  }

  function start() {
    heroInterval = setInterval(() => {
      showSlide((currentIndex + 1) % heroSlides.length);
    }, 5000);
  }

  window.heroJump = (index) => {
    if (index < 0 || index >= heroSlides.length) return;
    clearInterval(heroInterval);
    showSlide(index);
    start();
  };

  const heroSection = document.getElementById('hero-website');
  heroSection?.addEventListener('mouseenter', () => clearInterval(heroInterval));
  heroSection?.addEventListener('mouseleave', () => start());

  heroSlides.forEach((el, i) => { if (i > 0) loadBg(el); });

  start();
});
