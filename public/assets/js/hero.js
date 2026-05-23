document.addEventListener('DOMContentLoaded', () => {
  const heroSlides = document.querySelectorAll('.hero-item');
  const heroDots   = document.querySelectorAll('.dot');
  const cardTitle  = document.querySelector('.hero-card-title');
  const cardDesc   = document.querySelector('.hero-card-desc');

  if (!heroSlides.length) return;

  let currentIndex = 0;
  let heroInterval;

  function loadBg(el) {
    const slug = el.dataset.slug;
    if (!slug) return;
    const isMobile = window.innerWidth <= 768;
    el.classList.add(isMobile ? `hero-slide-${slug}-mobile` : `hero-slide-${slug}`);
    delete el.dataset.slug;
  }

  function updateCard(title, desc) {
    cardTitle.classList.add('is-fading');
    cardDesc.classList.add('is-fading');
    setTimeout(() => {
      cardTitle.textContent = title;
      cardDesc.textContent  = desc;
      cardTitle.classList.remove('is-fading');
      cardDesc.classList.remove('is-fading');
    }, 350);
  }

  function showSlide(index) {
    const slide = heroSlides[index];
    heroSlides.forEach(el => el.classList.remove('active'));
    heroDots.forEach(el => el.classList.remove('active'));
    slide.classList.add('active');
    heroDots[index].classList.add('active');
    loadBg(slide);
    updateCard(slide.dataset.title, slide.dataset.desc);
    currentIndex = index;
  }

  function nextSlide() {
    showSlide((currentIndex + 1) % heroSlides.length);
  }

  function start() {
    heroInterval = setInterval(nextSlide, 5000);
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

  requestIdleCallback?.(() => {
    heroSlides.forEach((el, i) => { if (i > 0) loadBg(el); });
  });

  start();
});
