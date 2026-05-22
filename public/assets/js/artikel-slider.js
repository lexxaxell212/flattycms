/**
 * artikel-slider.js
 * Scoped to #artikel-slider-wrapper. No CSS transitions — pure snap.
 */
(function () {
  "use strict";

  const BREAKPOINTS = { mobile: 1, tablet: 2, desktop: 4 };
  const GAP = 16;

  function getVisible() {
    const w = window.innerWidth;
    if (w >= 1200) return BREAKPOINTS.desktop;
    if (w >= 768)  return BREAKPOINTS.tablet;
    return BREAKPOINTS.mobile;
  }

  function initArtikelSlider(wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;

    const track    = wrapper.querySelector(".artikel-slider-track");
    const cards    = Array.from(track.querySelectorAll(".artikel-slide-card"));
    const dotsWrap = wrapper.querySelector(".artikel-slider-dots");
    const btnPrev  = wrapper.querySelector(".artikel-btn-prev");
    const btnNext  = wrapper.querySelector(".artikel-btn-next");
    const total    = cards.length;

    if (total === 0) return;

    // Always no transition
    track.style.transition = "none";

    let current   = 0;
    let startX    = 0;
    let touching  = false;

    function maxIndex() {
      return Math.max(0, total - getVisible());
    }

    function snap(offsetX) {
      const cardW = cards[0].offsetWidth;
      const step  = cardW + GAP;
      track.style.transform = `translateX(${-(current * step) + (offsetX || 0)}px)`;
    }

    function updateUI() {
      snap();
      const dots     = dotsWrap.querySelectorAll(".artikel-dot");
      const activeIdx = Math.floor(current / getVisible());
      dots.forEach((d, i) => {
        d.classList.toggle("is-active", i === activeIdx);
        d.setAttribute("aria-current", i === activeIdx ? "true" : "false");
      });
      if (btnPrev) btnPrev.disabled = current === 0;
      if (btnNext) btnNext.disabled = current >= maxIndex();
    }

    function buildDots() {
      dotsWrap.innerHTML = "";
      const vis      = getVisible();
      const dotCount = Math.ceil(total / vis);
      for (let i = 0; i < dotCount; i++) {
        const dot = document.createElement("button");
        dot.className = "artikel-dot";
        dot.setAttribute("aria-label", `Halaman ${i + 1}`);
        dot.addEventListener("click", () => {
          current = Math.min(i * vis, maxIndex());
          updateUI();
        });
        dotsWrap.appendChild(dot);
      }
    }

    // Nav buttons
    if (btnPrev) btnPrev.addEventListener("click", () => {
      current = Math.max(current - 1, 0);
      updateUI();
    });
    if (btnNext) btnNext.addEventListener("click", () => {
      current = Math.min(current + 1, maxIndex());
      updateUI();
    });

    // Touch swipe — follow finger, snap on release
    track.addEventListener("touchstart", e => {
      startX   = e.touches[0].clientX;
      touching = true;
    }, { passive: true });

    track.addEventListener("touchmove", e => {
      if (!touching) return;
      const delta = e.touches[0].clientX - startX;
      snap(delta);
    }, { passive: true });

    track.addEventListener("touchend", e => {
      if (!touching) return;
      touching = false;
      const delta     = e.changedTouches[0].clientX - startX;
      const threshold = cards[0].offsetWidth * 0.25;
      if (delta < -threshold) {
        current = Math.min(current + 1, maxIndex());
      } else if (delta > threshold) {
        current = Math.max(current - 1, 0);
      }
      updateUI();
    });

    // Resize
    let resizeTimer;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        current = Math.min(current, maxIndex());
        buildDots();
        updateUI();
      }, 120);
    });

    buildDots();
    updateUI();
  }

  document.addEventListener("DOMContentLoaded", () => {
    initArtikelSlider("artikel-slider-wrapper");
  });
})();