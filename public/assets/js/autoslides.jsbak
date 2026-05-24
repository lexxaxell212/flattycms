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