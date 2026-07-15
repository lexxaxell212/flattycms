<style>
 .eyebrow-animate {
  position: relative;
  display: inline-block;
  overflow: hidden;
  color: var(--text-white);
  opacity: 1;
  transition: opacity 1s ease;
  font-size: clamp(0.85rem, 1.5vw, 1rem);
  font-weight: var(--fw-semibold);
  letter-spacing: 0.15em;
  text-transform: uppercase;
  margin-bottom: 0.25rem;
  padding: 0.5rem;
  border-radius: 0.75rem;
 }
 .eyebrow-animate::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 0;
  background: var(--bg-primary);
  z-index: -1;
 }
 .eyebrow-animate.play::before {
  animation: eyebrowWipe 4s var(--ease) 1;
 }
 .eyebrow-animate.fade-out {
  opacity: 0;
 }
 @keyframes eyebrowWipe {
  0% {
   left: 0;
   width: 0%;
  }
  50% {
   left: 0;
   width: 100%;
  }
  100% {
   left: 100%;
   width: 0%;
  }
 }
 .hero-titles.title-animate {
  position: relative;
  display: inline-flex;
  align-items: baseline;
  overflow: hidden;
  width: 100%;
  white-space: nowrap;
  font-size: 2.5rem;
  font-weight: var(--fw-black);
  line-height: 1;
  color: var(--text-white);
  letter-spacing: -0.02em;
  margin-bottom: 1rem;
 }
 .hero-titles .title-prefix {
  position: absolute;
  left: 0;
  top: 0;
  display: inline-block;
  opacity: 0;
  transform: translateX(-150px);
 }
 .hero-titles .title-main {
  display: inline-block;
  transform: translateX(0);
 }
 .title-animate.play .title-prefix {
  animation: prefixSlide 4s var(--ease) 1;
 }
 .title-animate.play .title-main {
  animation: mainShift 4s var(--ease) 1;
 }
 @keyframes prefixSlide {
  0% {
   opacity: 0;
   transform: translateX(-20px);
  }
  30% {
   opacity: 1;
   transform: translateX(0);
  }
  70% {
   opacity: 1;
   transform: translateX(0);
  }
  100% {
   opacity: 0;
   transform: translateX(-20px);
  }
 }
 @keyframes mainShift {
  0% {
   transform: translateX(0);
  }
  30% {
   transform: translateX(125px);
  }
  70% {
   transform: translateX(125px);
  }
  100% {
   transform: translateX(0);
  }
 }
 @media (min-width: 768px) and (max-width: 1199px) {
  .hero-content-inner {
   text-align: left;
  }
 }
 @media (min-width: 1200px) {
  .hero-titles.title-animate {
   font-size: 5rem;
  }
  @keyframes prefixSlide {
   0% {
    opacity: 0;
    transform: translateX(-40px);
   }
   30% {
    opacity: 1;
    transform: translateX(0);
   }
   70% {
    opacity: 1;
    transform: translateX(0);
   }
   100% {
    opacity: 0;
    transform: translateX(-40px);
   }
  }
  @keyframes mainShift {
   0% {
    transform: translateX(0);
   }
   30% {
    transform: translateX(250px);
   }
   70% {
    transform: translateX(250px);
   }
   100% {
    transform: translateX(0);
   }
  }
 }
</style>
<div id="hero-website" class="hero-wrapper position-relative overflow-hidden">
 <div class="hero-item"></div>
 <div class="hero-overlay"></div>
 <div class="hero-overlay-global"></div>
 <div class="hero-card-wrapper">
  <div class="container hero-content align-items-end">
   <div class="row g-4 hero-content-inner">
    <div class="col-12">
     <span class="eyebrow-animate" data-bhs="hero.eyebrow">Explore</span>
     <br>
     <h1 class="hero-titles title-animate">
      <span class="title-prefix">AYO KE&nbsp;</span>
      <span class="title-main" data-bhs="hero.title">BANDUNG</span>
     </h1>
     <p class="hero-lead" data-bhs="hero.excerpt">
      Mulai dari mana? Mau kemana? Ngapain aja?
     </p>
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
</div>
<script>
 (function () {
  const eyebrow = document.querySelector(".eyebrow-animate");
  const title = document.querySelector(".title-animate");
  if (!eyebrow || !title) return;

  function playEyebrow() {
   eyebrow.classList.remove("play");
   eyebrow.classList.remove("fade-out");
   requestAnimationFrame(() => {
    requestAnimationFrame(() => {
     eyebrow.classList.add("play");
    });
   });
  }

  function playTitle() {
   title.classList.remove("play");
   void title.offsetWidth;
   title.classList.add("play");
  }

  eyebrow.addEventListener("animationend", function (e) {
   if (e.animationName !== "eyebrowWipe") return;
   eyebrow.classList.add("fade-out");
   setTimeout(playTitle, 400);
  });

  title.querySelector(".title-main").addEventListener("animationend",
   function () {
    title.classList.remove("play");
    playEyebrow();
   });

  playEyebrow();
 })();
</script>
<main class="main-content">