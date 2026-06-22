(function () {
  "use strict";

  const GAP = 16;

  function getVisible() {
    const w = window.innerWidth;
    if (w >= 1200) return 4;
    if (w >= 768) return 2;
    return 1;
  }

  function initArtikelSlider(wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;

    const viewport = wrapper.querySelector(".artikel-slider-viewport");
    const track = wrapper.querySelector(".artikel-slider-track");
    const cards = Array.from(track.querySelectorAll(".artikel-slide-card"));
    const dotsWrap = wrapper.querySelector(".artikel-slider-dots");
    const btnPrev = wrapper.querySelector(".artikel-btn-prev");
    const btnNext = wrapper.querySelector(".artikel-btn-next");
    const total = cards.length;

    if (total === 0) return;

    let current = 0;

    function cardStep() {
      return cards[0].getBoundingClientRect().width + GAP;
    }

    function maxIndex() {
      return Math.max(0, total - getVisible());
    }

    function scrollToIndex(idx) {
      current = Math.min(Math.max(idx, 0), maxIndex());
      viewport.scrollLeft = current * cardStep();
      updateUI();
    }

    function updateUI() {
      const dots = dotsWrap.querySelectorAll(".artikel-dot");
      const activeIdx = Math.floor(current / getVisible());
      dots.forEach((d, i) => {
        d.classList.toggle("is-active", i === activeIdx);
        d.setAttribute("aria-current", i === activeIdx ? "true": "false");
      });
      if (btnPrev) btnPrev.disabled = current === 0;
      if (btnNext) btnNext.disabled = current >= maxIndex();
    }

    function buildDots() {
      dotsWrap.innerHTML = "";
      const vis = getVisible();
      const dotCount = Math.ceil(total / vis);
      for (let i = 0; i < dotCount; i++) {
        const dot = document.createElement("button");
        dot.className = "artikel-dot";
        dot.setAttribute("aria-label", `Halaman ${i + 1}`);
        dot.addEventListener("click", () => scrollToIndex(i * vis));
        dotsWrap.appendChild(dot);
      }
    }

    viewport.addEventListener("scrollend", () => {
      const step = cardStep();
      current = Math.round(viewport.scrollLeft / step);
      current = Math.min(current, maxIndex());
      updateUI();
    });

    let scrollTimer;
    viewport.addEventListener("scroll", () => {
      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(() => {
        const step = cardStep();
        current = Math.round(viewport.scrollLeft / step);
        current = Math.min(current, maxIndex());
        updateUI();
      }, 80);
    });

    if (btnPrev) btnPrev.addEventListener("click", () => scrollToIndex(current - 1));
    if (btnNext) btnNext.addEventListener("click", () => scrollToIndex(current + 1));

    let resizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        current = Math.min(current, maxIndex());
        buildDots();
        viewport.style.scrollBehavior = "auto";
        viewport.scrollLeft = current * cardStep();
        viewport.style.scrollBehavior = "";
        updateUI();
      }, 500);
    });

    buildDots();
    updateUI();
  }

  document.addEventListener("DOMContentLoaded", () => {
    initArtikelSlider("artikel-slider-wrapper");
  });
})();