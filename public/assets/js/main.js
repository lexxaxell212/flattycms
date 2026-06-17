// chat
const API_URL = "/api/api-assistant.php";
const TOPIC = "bandung";
const TIMEOUT = 20000;

const SUGGEST_PROMPTS = [
  "🍽️ Kuliner enak di Bandung",
  "🏔️ Wisata alam sekitar",
  "🎡 Weekend seru di Bandung",
  "🕌 Tempat bersejarah",
  "🛍️ Rekomendasi belanja",
  "🌙 Tempat nongkrong malam",
];

let conversationHistory = [];
let isLoading = false;

function escapeHTML(str) {
  return str
  .replace(/&/g, "&amp;")
  .replace(/</g, "&lt;")
  .replace(/>/g, "&gt;")
  .replace(/"/g, "&quot;")
  .replace(/'/g, "&#039;");
}

function formatText(text) {
  let out = escapeHTML(text);
  out = out.replace(/\*\*(.+?)\*\*/g, '$1');
  // parse markdown link → <a>
  out = out.replace(
    /\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/g,
    '<a href="$2" target="_blank" rel="noopener noreferrer" class="chat-link">$1</a>'
  );
  out = out.replace(/^- (.+)/gm, '<li>$1</li>');
  out = out.replace(/(<li>.*<\/li>\n?)+/g, '<ul>$&</ul>');
  out = out.replace(/\n/g, '<br>');
  return out;
}

function currentTime() {
  return new Date().toLocaleTimeString([], {
    hour: "2-digit", minute: "2-digit"
  });
}

function scrollToBottom() {
  const messages = document.getElementById("chat-messages");
  messages.scrollTop = messages.scrollHeight;
}

function removeLoadingBubble(id) {
  document.getElementById(id)?.remove();
}

function removeSuggests() {
  document.querySelectorAll(".suggest-row").forEach(el => el.remove());
}

function addSuggests(prompts) {
  const messages = document.getElementById("chat-messages");
  const row = document.createElement("div");
  row.className = "suggest-row";
  prompts.forEach(text => {
    const chip = document.createElement("button");
    chip.className = "suggest-chip";
    chip.textContent = text;
    chip.onclick = () => {
      document.getElementById("message-input").value = text.replace(/^[\p{Emoji}\s]+/u, "").trim();
      removeSuggests();
      sendMessage();
    };
    row.appendChild(chip);
  });
  messages.appendChild(row);
  scrollToBottom();
}

function addMessage(text, type = "loading") {
  const messages = document.getElementById("chat-messages");
  const wrap = document.createElement("div");

  wrap.id = `msg_${Date.now()}_${Math.random().toString(36).slice(2, 6)}`;
  wrap.className = `message ${type}`;

  const bubble = document.createElement("div");
  bubble.className = "bubble";

  if (type === "loading") {
    bubble.innerHTML = `<div class="typing-indicator"><span></span><span></span><span></span></div>`;
  } else if (type === "error") {
    bubble.innerHTML = `<span class="error-text">⚠️ ${escapeHTML(text)}</span>`;
  } else {
    const label = type === "user" ? "You": "Yara";
    bubble.innerHTML = `
    <div class="msg-label">${label}</div>
    <div class="msg-text">${formatText(text)}</div>
    <div class="msg-time">${currentTime()}</div>`;
  }

  wrap.appendChild(bubble);
  messages.appendChild(wrap);
  scrollToBottom();
  return wrap.id;
}

async function sendMessage() {
  if (isLoading) return;

  const input = document.getElementById("message-input");
  const message = input.value.trim();
  if (!message) return;

  removeSuggests();
  addMessage(message, "user");
  input.value = "";
  input.style.height = "auto";
  input.focus();

  conversationHistory.push({
    role: "user", content: message
  });

  const loadingId = addMessage("", "loading");
  isLoading = true;

  try {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), TIMEOUT);

    const response = await fetch(API_URL, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest"
      },
      body: JSON.stringify({
        message, topic: TOPIC, history: conversationHistory
      }),
      signal: controller.signal
    });

    clearTimeout(timeout);
    const data = await response.json();
    removeLoadingBubble(loadingId);

    if (data.success) {
      addMessage(data.reply, "ai");
      conversationHistory.push({
        role: "assistant", content: data.reply
      });
      // Show suggest chips after first AI reply if history is short
      if (conversationHistory.length <= 2) {
        addSuggests(SUGGEST_PROMPTS.slice(0, 4));
      }
    } else {
      addMessage(data.error ?? "Terjadi kesalahan.", "error");
      conversationHistory.pop();
    }
  } catch (err) {
    removeLoadingBubble(loadingId);
    const msg = err.name === "AbortError"
    ? "Request timeout.": "Koneksi bermasalah.";
    addMessage(msg, "error");
    conversationHistory.pop();
  } finally {
    isLoading = false;
  }
}

function clearChat() {
  document.getElementById("chat-messages").innerHTML = "";
  conversationHistory = [];
  addMessage("Chat dibersihkan. Ada yang bisa Yara bantu lagi? 😊", "ai");
  addSuggests(SUGGEST_PROMPTS);
}

function autoResize(el) {
  el.style.height = "auto";
  el.style.height = Math.min(el.scrollHeight, 120) + "px";
}

document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("message-input");

  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  input.addEventListener("input",
    () => autoResize(input));

  addMessage("Wilujeng sumping! 👋 Yara siap bantu eksplor Bandung. Mau tanya apa nih..?",
    "ai");
  addSuggests(SUGGEST_PROMPTS);
  input.focus();
});

// live search

(function () {
  "use strict";

  const CONFIG = {
    SEARCH_URL: "/api/api-search.php",
    MIN_CHARS: 2,
    DEBOUNCE_MS: 300,

    TRIGGER: "#btn-search",
    WRAPPER: "#live-search-wrapper",
    INPUT: "#searchInput2",
    DROPDOWN: "#live-search-dropdown",
    CLOSE: "#ls-close-btn",

    LABELS: {
      cmpt: "Things To Do",
      allcontent_posts: "Blogs",
      poi: "POI Bandung"
    },
    URLS: {
      cmpt: "{button_link}",
      allcontent_posts: "/blogs/?slug={slug}",
      poi: "/poi/{slug}"
    }
  };

  let debounceTimer = null;
  let currentRequest = null;

  const wrapper = document.querySelector(CONFIG.WRAPPER);
  const trigger = document.querySelector(CONFIG.TRIGGER);
  const input = document.querySelector(CONFIG.INPUT);
  const dropdown = document.querySelector(CONFIG.DROPDOWN);
  const closeBtn = document.querySelector(CONFIG.CLOSE);

  function init() {
    if (!wrapper || !trigger || !input || !dropdown) {
      console.warn("[live-search] Elemen tidak ditemukan.");
      return;
    }

    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      openWrapper();
    });

    closeBtn?.addEventListener("click", closeWrapper);

    input.addEventListener("input", onInput);
    input.addEventListener("keydown", onKeydown);

    document.addEventListener("click", (e) => {
      if (!wrapper.contains(e.target) && !trigger.contains(e.target)) {
        closeWrapper();
      }
    });

    document.addEventListener("keydown",
      (e) => {
        if (e.key === "Escape") closeWrapper();
      });

    window.OverlayManager?.register("search",
      {
        close: () => closeWrapper( {
          silent: true
        })
      });
  }

  function openWrapper() {
    window.OverlayManager?.openExclusive("search");

    wrapper.classList.add("active");
    trigger.classList.add("is-active");
    window.ScrollLock?.lock();
    setTimeout(() => input.focus(),
      50);
    clearDropdown();
  }

  function closeWrapper(opts = {}) {
    const wasActive = wrapper.classList.contains("active");
    wrapper.classList.remove("active");
    trigger.classList.remove("is-active");
    dropdown.classList.remove("open");
    if (wasActive) window.ScrollLock?.unlock();
    input.value = "";
    clearDropdown();

    if (!opts.silent) {
      window.OverlayManager?.notifyClosed("search");
    }
  }
  window.closeSearch = closeWrapper;

  function onInput() {
    const q = input.value.trim();
    clearTimeout(debounceTimer);

    if (q.length < CONFIG.MIN_CHARS) {
      dropdown.classList.remove("open");
      clearDropdown();
      return;
    }

    debounceTimer = setTimeout(() => fetchResults(q), CONFIG.DEBOUNCE_MS);
  }

  function fetchResults(q) {
    if (currentRequest) currentRequest.abort();
    currentRequest = new AbortController();

    showLoading();

    fetch(CONFIG.SEARCH_URL + "?q=" + encodeURIComponent(q), {
      signal: currentRequest.signal,
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    })
    .then((res) => {
      if (!res.ok) throw new Error("HTTP " + res.status);
      return res.json();
    })
    .then((data) => renderResults(data, q))
    .catch((err) => {
      if (err.name === "AbortError") return;
      showError();
      console.error("[live-search]", err);
    });
  }

  function renderResults(data,
    q) {
    dropdown.innerHTML = "";

    if (!data.results || data.results.length === 0) {
      dropdown.innerHTML =
      '<div class="ls-empty">Tidak ada hasil untuk <strong>' +
      esc(q) +
      "</strong></div>";
      dropdown.classList.add("open");
      return;
    }

    const grouped = {};
    data.results.forEach((item) => {
      if (!grouped[item.source]) grouped[item.source] = [];
      grouped[item.source].push(item);
    });

    const sources = Object.keys(grouped);
    let itemIndex = 0;

    sources.forEach((source, si) => {
      const label = document.createElement("div");
      label.className = "ls-group-label";
      label.textContent = CONFIG.LABELS[source] || source;
      dropdown.appendChild(label);

      grouped[source].forEach((item) => {
        let href = CONFIG.URLS[source] || "#";
        href = href.replace("{id}", item.id);
        if (item.slug) href = href.replace("{slug}", item.slug);
        if (item.button_link) href = href.replace("{button_link}", item.button_link);

        const a = document.createElement("a");
        a.className = "ls-item";
        a.href = href;
        a.setAttribute("role", "option");
        a.style.animationDelay = itemIndex * 40 + "ms";

        a.innerHTML =
        '<div class="ls-title">' +
        hilite(item.title, q) +
        "</div>" +
        (item.description
          ? '<div class="ls-desc">' +
          hilite(trunc(item.description, 90), q) +
          "</div>": "");

        dropdown.appendChild(a);
        itemIndex++;
      });

      if (si < sources.length - 1) {
        const div = document.createElement("div");
        div.className = "ls-divider";
        dropdown.appendChild(div);
      }
    });

    const footer = document.createElement("div");
    footer.className = "ls-footer";
    footer.textContent = data.total + " hasil ditemukan";
    dropdown.appendChild(footer);

    dropdown.classList.add("open");
  }

  function onKeydown(e) {
    const items = [...dropdown.querySelectorAll(".ls-item")];
    const focused = dropdown.querySelector(".ls-item.focused");
    let idx = items.indexOf(focused);

    if (e.key === "ArrowDown") {
      e.preventDefault();
      moveFocus(items, idx + 1 < items.length ? idx + 1: 0);
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      moveFocus(items, idx - 1 >= 0 ? idx - 1: items.length - 1);
    } else if (e.key === "Enter" && focused) {
      e.preventDefault();
      focused.click();
    }
  }

  function moveFocus(items, idx) {
    items.forEach((el) => el.classList.remove("focused"));
    if (items[idx]) {
      items[idx].classList.add("focused");
      items[idx].scrollIntoView({
        block: "nearest"
      });
    }
  }

  function clearDropdown() {
    dropdown.innerHTML = "";
    dropdown.classList.remove("open");
  }

  function showLoading() {
    dropdown.innerHTML =
    '<div class="ls-loading"><span class="ls-spinner"></span> Mencari...</div>';
    dropdown.classList.add("open");
  }

  function showError() {
    dropdown.innerHTML =
    '<div class="ls-error">Terjadi kesalahan, coba lagi.</div>';
    dropdown.classList.add("open");
  }

  function hilite(text, q) {
    if (!q || !text) return esc(text || "");
    const re = new RegExp(
      "(" + q.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")",
      "gi"
    );
    return esc(text).replace(re, "<mark>$1</mark>");
  }

  function trunc(str, len) {
    return str && str.length > len ? str.slice(0, len) + "…": str;
  }

  function esc(str) {
    const d = document.createElement("div");
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();


const ScrollLock = {
  _count: 0,

  lock() {
    this._count++;
    if (this._count === 1) {
      const scrollbarWidth = this._getScrollbarWidth();
      document.documentElement.style.setProperty("--scrollbar-width", scrollbarWidth + "px");
      document.documentElement.style.overflowY = "hidden";
      document.body.style.overflow = "hidden";
      document.body.style.paddingRight = scrollbarWidth + "px";
    }
  },

  unlock() {
    this._count = Math.max(0, this._count - 1);
    if (this._count === 0) {
      document.documentElement.style.removeProperty("--scrollbar-width");
      document.documentElement.style.overflowY = "";
      document.body.style.overflow = "";
      document.body.style.paddingRight = "";
    }
  },

  reset() {
    this._count = 0;
    document.documentElement.style.removeProperty("--scrollbar-width");
    document.documentElement.style.overflowY = "";
    document.body.style.overflow = "";
    document.body.style.paddingRight = "";
  },

  _getScrollbarWidth() {
    return window.innerWidth - document.documentElement.clientWidth;
  }
};
window.ScrollLock = ScrollLock;


const OverlayManager = {
  _overlays: {},
  _active: null,

  register(name, handlers) {
    this._overlays[name] = handlers;
  },

  async openExclusive(name) {
    if (this._active && this._active !== name) {
      await this._closeByName(this._active);
    }
    this._active = name;
  },

  notifyClosed(name) {
    if (this._active === name) {
      this._active = null;
    }
  },

  async _closeByName(name) {
    const handler = this._overlays[name];
    if (!handler) return;
    try {
      await handler.close();
    } catch (err) {
      console.warn("[OverlayManager] gagal menutup overlay:", name, err);
    }
    if (this._active === name) this._active = null;
  }
};
window.OverlayManager = OverlayManager;


// navbar

const toggler = document.getElementById("navbarToggler");
const menuOverlay = document.getElementById("menuOverlay");
const navbarCollapse = document.getElementById("navbarNav-mobile");

function openMenu() {
  menuOverlay.classList.add("menu-open");
  navbarCollapse.classList.add("menu-open");
  toggler.classList.add("menu-open");
  ScrollLock.lock();
}

function closeMenu(opts = {}) {
  const wasOpen = menuOverlay.classList.contains("menu-open");
  menuOverlay.classList.remove("menu-open");
  navbarCollapse.classList.remove("menu-open");
  toggler.classList.remove("menu-open");
  if (wasOpen) ScrollLock.unlock();

  if (!opts.silent) {
    OverlayManager.notifyClosed("menu");
  }
}

async function toggleMenu() {
  const isOpen = menuOverlay.classList.contains("menu-open");

  if (isOpen) {
    closeMenu();
  } else {
    await OverlayManager.openExclusive("menu");
    openMenu();
  }
}

OverlayManager.register("menu", {
  close: () => closeMenu( {
    silent: true
  })
});

if (toggler) toggler.addEventListener("click", toggleMenu);
if (menuOverlay) menuOverlay.addEventListener("click", toggleMenu);

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && menuOverlay?.classList.contains("menu-open")) {
    toggleMenu();
  }
});


function setupChatbotOverlay() {
  const chatbotElement = document.getElementById("chatbot");
  if (!chatbotElement || !window.bootstrap || !window.bootstrap.Offcanvas) return;

  const isMobileChatbot = () => window.matchMedia("(max-width: 767.98px)").matches;
  let lockedThisOpen = false;

  const instance =
  window.bootstrap.Offcanvas.getInstance(chatbotElement) ??
  new window.bootstrap.Offcanvas(chatbotElement, {
    scroll: true,
    backdrop: false
  });

  chatbotElement.addEventListener("show.bs.offcanvas", () => {
    lockedThisOpen = isMobileChatbot();
    if (lockedThisOpen) ScrollLock.lock();
  });

  chatbotElement.addEventListener("hidden.bs.offcanvas",
    () => {
      if (lockedThisOpen) {
        ScrollLock.unlock();
        lockedThisOpen = false;
      }
      OverlayManager.notifyClosed("chatbot");
    });

  OverlayManager.register("chatbot",
    {
      close: () => {
        if (!chatbotElement.classList.contains("show")) {
          return Promise.resolve();
        }
        return new Promise((resolve) => {
          chatbotElement.addEventListener(
            "hidden.bs.offcanvas",
            () => resolve(),
            {
              once: true
            }
          );
          instance.hide();
        });
      }
    });

  return instance;
}

async function openChatbot() {
  const chatbotElement = document.getElementById("chatbot");
  if (!chatbotElement || !window.bootstrap || !window.bootstrap.Offcanvas) return;

  await OverlayManager.openExclusive("chatbot");

  const instance =
  window.bootstrap.Offcanvas.getInstance(chatbotElement) ??
  new window.bootstrap.Offcanvas(chatbotElement, {
    scroll: true, backdrop: false
  });
  instance.show();
}

function closeChatbot() {
  const chatbotElement = document.getElementById("chatbot");
  if (!chatbotElement || !window.bootstrap || !window.bootstrap.Offcanvas) return;
  const instance = window.bootstrap.Offcanvas.getInstance(chatbotElement);
  instance?.hide();
}

async function toggleChatbotGlobal() {
  const chatbotElement = document.getElementById("chatbot");
  if (!chatbotElement) return;
  const isOpen = chatbotElement.classList.contains("show");
  if (isOpen) {
    closeChatbot();
  } else {
    await openChatbot();
  }
}


class SmartFab {
  constructor(fabId = "chatbotFabBtn",
    scrollThreshold = 200) {
    this.fab = document.getElementById(fabId);
    this.threshold = scrollThreshold;
    this.isVisible = false;
    this.init();
  }

  init() {
    if (!this.fab) return;
    this.updateVisibility();
    this._scrollHandler = this.throttle(this.handleScroll.bind(this), 16);
    window.addEventListener("scroll", this._scrollHandler, {
      passive: true
    });
    window.addEventListener("beforeunload", () => this.destroy());
    this.fab.addEventListener("click", this.onFabClick);
  }

  onFabClick = () => {
    toggleChatbotGlobal();
  };

  handleScroll() {
    this.updateVisibility();
  }

  updateVisibility() {
    const shouldShow = window.scrollY >= this.threshold;
    if (shouldShow && !this.isVisible) this.show();
    else if (!shouldShow && this.isVisible) this.hide();
  }

  show() {
    this.fab.style.opacity = "1";
    this.fab.style.transform = "scale(1) translateY(0)";
    this.fab.style.visibility = "visible";
    this.fab.style.animation = "fabSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1)";
    this.isVisible = true;
  }

  hide() {
    this.fab.style.opacity = "0";
    this.fab.style.transform = "scale(0.8) translateY(20px)";
    this.isVisible = false;
    setTimeout(() => {
      if (!this.isVisible) this.fab.style.visibility = "hidden";
    },
      300);
  }

  throttle(func,
    limit) {
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
    window.removeEventListener("scroll", this._scrollHandler);
    this.fab.removeEventListener("click", this.onFabClick);
  }
}

class SmartScrollTop {
  constructor(btnId = "scrollTopBtn") {
    this.btn = document.getElementById(btnId);
    this.isVisible = false;
    this.init();
  }

  init() {
    if (!this.btn) return;
    this._scrollHandler = this.throttle(this.handleScroll.bind(this), 16);
    this._resizeHandler = this.throttle(this.handleResize.bind(this), 250);
    this.updateVisibility();
    window.addEventListener("scroll", this._scrollHandler, {
      passive: true
    });
    window.addEventListener("resize", this._resizeHandler);
    this.btn.addEventListener("click", this.scrollToTop.bind(this));
  }

  destroy() {
    window.removeEventListener("scroll", this._scrollHandler);
    window.removeEventListener("resize", this._resizeHandler);
    this.btn?.removeEventListener("click", this.scrollToTop);
  }

  handleScroll() {
    const nearBottom = this.isNearBottom();
    if (nearBottom && !this.isVisible) this.show();
    else if (!nearBottom && this.isVisible) this.hide();
  }

  handleResize() {
    this.updateVisibility();
  }
  updateVisibility() {
    this.handleScroll();
  }

  isNearBottom() {
    const docHeight = document.documentElement.scrollHeight;
    const scrolledFromBottom = docHeight - window.scrollY - window.innerHeight;
    return scrolledFromBottom <= docHeight * 0.25;
  }

  scrollToTop() {
    window.scrollTo({
      top: 0, behavior: "smooth"
    });
  }

  show() {
    this.btn.style.opacity = "1";
    this.btn.style.transform = "scale(1) translateY(0)";
    this.btn.style.visibility = "visible";
    this.btn.style.animation = "fabSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1)";
    this.isVisible = true;
  }

  hide() {
    this.btn.style.opacity = "0";
    this.btn.style.transform = "scale(0.7) translateY(20px)";
    this.isVisible = false;
    setTimeout(() => {
      if (!this.isVisible) this.btn.style.visibility = "hidden";
    },
      300);
  }

  throttle(func,
    limit) {
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
  el.classList.toggle("dark");
  localStorage.setItem(
    "dark",
    document.documentElement.hasAttribute("data-dark")
  );
}

document.addEventListener("DOMContentLoaded", () => {
  new SmartFab("chatbotFabBtn", 200);
  new SmartScrollTop("scrollTopBtn");

  setupChatbotOverlay();

  if (localStorage.getItem("dark") === "true") {
    document.documentElement.setAttribute("data-dark", "");
    document.getElementById("dmToggle")?.classList.add("dark");
  }
});

// weather

const weatherTranslations = {
  "cerah": "Cerah",
  "cerah berawan": "Cerah Berawan",
  "sedikit berawan": "Sedikit Berawan",
  "berawan": "Berawan",
  "mendung": "Mendung",
  "hujan ringan": "Hujan Ringan",
  "hujan sedang": "Hujan Sedang",
  "hujan lebat": "Hujan Lebat",
  "hujan sangat lebat": "Hujan Lebat",
  "hujan ekstrem": "Hujan Ekstrem",
  "gerimis ringan": "Gerimis",
  "gerimis": "Gerimis",
  "gerimis lebat": "Gerimis Lebat",
  "badai petir": "Badai Petir",
  "badai petir dengan hujan ringan": "Badai Petir",
  "badai petir dengan hujan lebat": "Badai Petir Lebat",
  "hujan salju ringan": "Hujan Salju",
  "hujan salju": "Hujan Salju",
  "salju lebat": "Salju Lebat",
  "hujan es": "Hujan Es",
  "kabut": "Kabut",
  "kabut tipis": "Kabut Tipis",
  "asap": "Asap",
  "debu": "Debu",
  "pasir": "Pasir",
  "abu": "Abu",
  "angin puyuh": "Angin Puyuh",
  "tornado": "Tornado",
  "awan pecah": "Berawan",
  "awan bergerak cepat": "Berawan"
};

let isRefreshing = false;

async function u() {
  if (isRefreshing) return;
  isRefreshing = true;

  const refreshIcon = document.querySelector("#w .fa-refresh");
  refreshIcon?.classList.add("fa-spin");

  try {
    await new Promise(r => setTimeout(r, 500));
    const r = await fetch("/api/api-weather.php?city=Bandung",
      {
        headers: {
          "X-Requested-With": "XMLHttpRequest"
        }
      });
    const d = await r.json();
    if (d.error) throw new Error(d.error);

    const rawDesc = d.weather[0].description.toLowerCase();
    let weatherDesc =
    weatherTranslations[rawDesc] ||
    rawDesc.charAt(0).toUpperCase() + rawDesc.slice(1);

    document.getElementById("w").innerHTML = `
    <button type="button" class="position-absolute top-0 end-0 badge text-muted fw-bold border-0 bg-transparent" style="font-size:1rem" onclick="u()" aria-label="Refresh cuaca" title="Refresh cuaca">
    <i class="fas fa-refresh"></i>
    </button>
    <div class="icon-w">
    ${g(d.weather[0].main)}
    </div>
    <div>
    <div class="temp">${Math.round(d.main.temp)}<sup>°C</sup></div>
    <div class="cond">${weatherDesc}</div>
    </div>
    <div class="sep"></div>
    <div class="d-flex align-items-center gap-1 city">
    <i class="fa-solid fa-location-dot me-1"></i>
    ${d.name}
    </div>
    `;
  } catch (error) {
    console.error("Error fetching weather data:", error);
    document.getElementById("w").innerHTML =
    '<button type="button" class="position-absolute top-0 end-0 badge text-muted fw-bold border-0 bg-transparent" style="font-size:1rem" onclick="u()" aria-label="Refresh cuaca" title="Refresh cuaca"><i class="fas fa-refresh"></i></button><div class="badge badge-red small"><i class="fas fa-triangle-exclamation me-2"></i>Gagal memuat cuaca</div>';
  } finally {
    isRefreshing = false;
  }
}

function g(c) {
  return (
    {
      Clear: "☀️",
      Clouds: "☁️",
      Rain: "🌧️",
      Snow: "❄️",
      Thunderstorm: "⛈️",
      Drizzle: "🌦️",
      Atmosphere: "🌫️"
    }[c] || "🌤️"
  );
}

u();
setInterval(u, 15 * 60 * 1000);


// newsletter

document.addEventListener('DOMContentLoaded', function() {
  const allowed = ['gmail.com', 'googlemail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'proton.me'];
  const form = document.getElementById('newsletterForm');
  const input = document.getElementById('emailInput');
  const btn = document.getElementById('submitBtn');
  if (!form) return;

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = input.value.trim();
    if (email === '') {
      flattyToast('warning', 'Email-nya diisi dulu ya!');
      return;
    }
    const domain = email.split('@')[1];
    if (!allowed.includes(domain)) {
      flattyToast('warning', 'Gunakan email umum ya (Gmail/Yahoo/Outlook)');
      return;
    }
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.innerHTML = 'MENGIRIM <i class="fa-solid fa-circle-notch fa-spin ms-2"></i>';
    await new Promise(r => setTimeout(r, 500));
    const formData = new FormData(form);
    fetch(CONFIG.baseUrl + '/api/api-newsletter.php', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams(formData)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        flattyToast('success', data.message);
        input.value = '';
      } else {
        flattyToast('error', data.message);
      }
    })
    .catch(() => {
      flattyToast('error', 'Koneksi lagi bermasalah nih..');
    })
    .finally(() => {
      btn.disabled = false;
      btn.style.opacity = '1';
      btn.innerHTML = 'BERLANGGANAN <i class="fa-solid fa-paper-plane ms-2"></i>';
    });
  });
});

// sparkles
function spawnSparkle(el) {
  const colors = [
    "#7c3aed",
    "#9d5cf6",
    "#5b21b6",
    "#c084fc",
    "#a78bfa",
    "#e879f9",
    "#f0abfc",
  ];
  const count = 8;

  for (let i = 0; i < count; i++) {
    const dot = document.createElement("span");
    dot.classList.add("sparkle-particle");

    const angle = (360 / count) * i;
    const dist = 20 + Math.random() * 20;
    const rad = (angle * Math.PI) / 180;
    const tx = Math.cos(rad) * dist;
    const ty = Math.sin(rad) * dist;

    dot.style.setProperty("--tx", `${tx}px`);
    dot.style.setProperty("--ty", `${ty}px`);
    dot.style.background = colors[Math.floor(Math.random() * colors.length)];
    dot.style.left = "50%";
    dot.style.top = "50%";
    dot.style.marginLeft = "-3px";
    dot.style.marginTop = "-3px";
    dot.style.animationDelay = `${Math.random() * 80}ms`;

    el.appendChild(dot);
    dot.addEventListener("animationend", () => dot.remove());
  }
}

document.querySelectorAll(".sparkle-origin").forEach((el) => {
  el.addEventListener("click", () => spawnSparkle(el));
});

// textRotator
function initTextRotator(el) {
  let index = 0;

  setInterval(() => {
    const texts = el.dataset.rotate.split("|");
    el.style.opacity = "0";
    setTimeout(() => {
      index = (index + 1) % texts.length;
      el.textContent = texts[index];
      el.style.opacity = "1";
    }, 400);
  }, 5000);
}

document.querySelectorAll("[data-rotate]").forEach(initTextRotator);

// ticker text
function initTicker(el) {
  let index = 0;

  setInterval(() => {
    const texts = el.dataset.ticker.split("|");
    el.style.transform = "translateY(-30%)";
    el.style.opacity = "0";
    setTimeout(() => {
      index = (index + 1) % texts.length;
      el.textContent = texts[index];
      el.style.transition = "none";
      el.style.transform = "translateY(30%)";
      el.style.opacity = "0";
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          el.style.transition = "transform 400ms cubic-bezier(0.16, 1, 0.3, 1), opacity 400ms ease";
          el.style.transform = "translateY(0)";
          el.style.opacity = "1";
        });
      });
    }, 400);
  }, 5000);
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-ticker]").forEach(initTicker);
});

// reveal
document.addEventListener('DOMContentLoaded', () => {
  const sections = document.querySelectorAll('section');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1
  });

  sections.forEach(el => observer.observe(el));
});