const toggler       = document.getElementById("navbarToggler");
const menuOverlay   = document.getElementById("menuOverlay");
const navbarCollapse = document.getElementById("navbarNav");

const ScrollLock = {
  _count: 0,

  lock() {
    this._count++;
    document.body.style.overflow = "hidden";
  },

  unlock() {
    this._count = Math.max(0, this._count - 1);
    if (this._count === 0) {
      document.body.style.overflow = "";
      document.body.style.paddingRight = "";
    }
  },

  reset() {
    this._count = 0;
    document.body.style.overflow = "";
    document.body.style.paddingRight = "";
  }
};

function closeOffcanvas() {
  const activeOffcanvas = document.querySelector(".offcanvas.show");
  if (activeOffcanvas && window.bootstrap?.Offcanvas) {
    const instance = window.bootstrap.Offcanvas.getInstance(activeOffcanvas);
    if (instance) instance.hide();
  }
}

function toggleMenu() {
  const isOpen = menuOverlay.classList.contains("menu-open");

  if (isOpen) {
    menuOverlay.classList.remove("menu-open");
    navbarCollapse.classList.remove("menu-open");
    toggler.classList.remove("menu-open");
    ScrollLock.unlock(); // [FIX] Pakai ScrollLock
  } else {
    closeOffcanvas();

    menuOverlay.classList.add("menu-open");
    navbarCollapse.classList.add("menu-open");
    toggler.classList.add("menu-open");
    ScrollLock.lock(); // [FIX] Pakai ScrollLock
  }
}

if (toggler)     toggler.addEventListener("click", toggleMenu);
if (menuOverlay) menuOverlay.addEventListener("click", toggleMenu);

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && menuOverlay?.classList.contains("menu-open")) {
    toggleMenu();
  }
});

class SmartFab {
  constructor(fabId = "chatbotFabBtn", scrollThreshold = 200) {
    this.fab       = document.getElementById(fabId);
    this.threshold = scrollThreshold;
    this.isVisible = false;
    this.init();
  }

  init() {
    if (!this.fab) return;
    this.updateVisibility();
    this._scrollHandler = this.throttle(this.handleScroll.bind(this), 16);
    window.addEventListener("scroll", this._scrollHandler, { passive: true });
    window.addEventListener("beforeunload", () => this.destroy());
    this.fab.addEventListener("click", this.onFabClick);
  }

  onFabClick = () => {
    const isMenuOpen = !!menuOverlay?.classList.contains("menu-open"); // [FIX] boolean eksplisit
    if (isMenuOpen) {
      toggleMenu(); // toggleMenu sudah panggil ScrollLock.unlock()
    }
    this.toggleChatbot();
  };

  toggleChatbot() {
    const chatbotElement = document.getElementById("chatbot");
    if (window.bootstrap?.Offcanvas && chatbotElement) {
      const modal =
        window.bootstrap.Offcanvas.getInstance(chatbotElement) ||
        new window.bootstrap.Offcanvas(chatbotElement);
      modal.toggle();
    }
  }

  handleScroll() {
    this.updateVisibility();
  }

  updateVisibility() {
    const shouldShow = window.scrollY >= this.threshold;
    if (shouldShow && !this.isVisible) {
      this.show();
    } else if (!shouldShow && this.isVisible) {
      this.hide();
    }
  }

  show() {
    this.fab.style.opacity    = "1";
    this.fab.style.transform  = "scale(1) translateY(0)";
    this.fab.style.visibility = "visible";
    this.fab.style.animation  = "fabSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1)";
    this.isVisible = true;
  }

  hide() {
    this.fab.style.opacity   = "0";
    this.fab.style.transform = "scale(0.8) translateY(20px)";
    this.isVisible = false;
    setTimeout(() => {
      if (!this.isVisible) this.fab.style.visibility = "hidden";
    }, 300);
  }

  throttle(func, limit) {
    let inThrottle;
    return function () {
      if (!inThrottle) {
        func.apply(this, arguments);
        inThrottle = true;
        setTimeout(() => (inThrottle = false), limit);
      }
    }.bind(this);
  }

  destroy() {
    window.removeEventListener("scroll", this._scrollHandler); // [FIX] referensi benar
    this.fab.removeEventListener("click", this.onFabClick);
  }
}

class SmartScrollTop {
  constructor(btnId = "scrollTopBtn") {
    this.btn       = document.getElementById(btnId);
    this.isVisible = false;
    this.init();
  }

  init() {
    if (!this.btn) return;

    this._scrollHandler = this.throttle(this.handleScroll.bind(this), 16);
    this._resizeHandler = this.throttle(this.handleResize.bind(this), 250);

    this.updateVisibility();
    window.addEventListener("scroll", this._scrollHandler, { passive: true });
    window.addEventListener("resize", this._resizeHandler);
    this.btn.addEventListener("click", this.scrollToTop.bind(this));
  }

  destroy() {
    window.removeEventListener("scroll", this._scrollHandler); // [FIX] referensi benar
    window.removeEventListener("resize", this._resizeHandler); // [FIX] referensi benar
    this.btn?.removeEventListener("click", this.scrollToTop);
  }

  handleScroll() {
    const nearBottom = this.isNearBottom();
    if (nearBottom && !this.isVisible)  this.show();
    else if (!nearBottom && this.isVisible) this.hide();
  }

  handleResize() {
    this.updateVisibility();
  }

  updateVisibility() {
    this.handleScroll();
  }

  isNearBottom() {
    const scrollTop         = window.scrollY;
    const docHeight         = document.documentElement.scrollHeight;
    const scrolledFromBottom = docHeight - scrollTop - window.innerHeight;
    return scrolledFromBottom <= docHeight * 0.25;
  }

  scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  show() {
    this.btn.style.opacity    = "1";
    this.btn.style.transform  = "scale(1) translateY(0)";
    this.btn.style.visibility = "visible";
    this.isVisible = true;
  }

  hide() {
    this.btn.style.opacity   = "0";
    this.btn.style.transform = "scale(0.7) translateY(20px)";
    this.isVisible = false;
    setTimeout(() => {
      if (!this.isVisible) this.btn.style.visibility = "hidden";
    }, 300);
  }

  throttle(func, limit) {
    let inThrottle;
    return function () {
      if (!inThrottle) {
        func.apply(this, arguments);
        inThrottle = true;
        setTimeout(() => (inThrottle = false), limit);
      }
    }.bind(this);
  }
}

function toggleDark(el) {
  document.documentElement.toggleAttribute("data-dark");
  el.classList.toggle("on");
  localStorage.setItem("dark", document.documentElement.hasAttribute("data-dark"));
}

document.addEventListener("DOMContentLoaded", () => {
  new SmartFab("chatbotFabBtn", 200);
  new SmartScrollTop("scrollTopBtn");

  const chatbotElement = document.getElementById("chatbot");

  if (chatbotElement) {
    chatbotElement.addEventListener("show.bs.offcanvas", () => {
      ScrollLock.lock();
    });

    chatbotElement.addEventListener("hidden.bs.offcanvas", () => {
      ScrollLock.unlock();
    });
  }

  if (localStorage.getItem("dark") === "true") {
    document.documentElement.setAttribute("data-dark", "");
    const switchEl = document.querySelector(".switch");
    if (switchEl) switchEl.classList.add("on");
  }
});