(function () {
  'use strict';

  const CONFIG = {
    SEARCH_URL  : '/api/api-search.php',
    MIN_CHARS   : 2,
    DEBOUNCE_MS : 300,

    TRIGGER  : '#btn-search',
    WRAPPER  : '#live-search-wrapper',
    INPUT    : '#searchInput2',
    DROPDOWN : '#live-search-dropdown',
    CLOSE    : '#ls-close-btn',

    LABELS : {
      admin_items      : 'Pages',
      allcontent_posts : 'Blogs',
      pages            : 'Pages',
    },

    URLS : {
      admin_items      : '/pages/?id={id}',
      allcontent_posts : '/blogs/?id={id}',
      pages            : '/pages/{slug}/',
    },
  };

  let debounceTimer  = null;
  let currentRequest = null;

  const wrapper  = document.querySelector(CONFIG.WRAPPER);
  const trigger  = document.querySelector(CONFIG.TRIGGER);
  const input    = document.querySelector(CONFIG.INPUT);
  const dropdown = document.querySelector(CONFIG.DROPDOWN);
  const closeBtn = document.querySelector(CONFIG.CLOSE);

  function init() {
    if (!wrapper || !trigger || !input || !dropdown) {
      console.warn('[live-search] Elemen tidak ditemukan.');
      return;
    }

    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      openWrapper();
    });

    closeBtn?.addEventListener('click', closeWrapper);

    input.addEventListener('input', onInput);
    input.addEventListener('keydown', onKeydown);

    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target) && !trigger.contains(e.target)) {
        closeWrapper();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeWrapper();
    });
  }

  function openWrapper() {
    if (document.getElementById('menuOverlay')?.classList.contains('menu-open')) {
    window.toggleMenu?.(); // close menu
    }
    wrapper.classList.add('active');
    trigger.classList.add('is-active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => input.focus(), 50);
    clearDropdown();
  }

  function closeWrapper() {
    wrapper.classList.remove('active');
    trigger.classList.remove('is-active');
    dropdown.classList.remove('open');
    document.body.style.overflow = '';
    input.value = '';
    clearDropdown();
  }
  window.closeSearch = closeWrapper; // expose global

  function onInput() {
    const q = input.value.trim();
    clearTimeout(debounceTimer);

    if (q.length < CONFIG.MIN_CHARS) {
      dropdown.classList.remove('open');
      clearDropdown();
      return;
    }

    debounceTimer = setTimeout(() => fetchResults(q), CONFIG.DEBOUNCE_MS);
  }

  function fetchResults(q) {
    if (currentRequest) currentRequest.abort();
    currentRequest = new AbortController();

    showLoading();

    fetch(CONFIG.SEARCH_URL + '?q=' + encodeURIComponent(q), {
      signal: currentRequest.signal,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
      })
      .then(data => renderResults(data, q))
      .catch(err => {
        if (err.name === 'AbortError') return;
        showError();
        console.error('[live-search]', err);
      });
  }

  function renderResults(data, q) {
    dropdown.innerHTML = '';

    if (!data.results || data.results.length === 0) {
      dropdown.innerHTML =
        '<div class="ls-empty">Tidak ada hasil untuk <strong>' + esc(q) + '</strong></div>';
      dropdown.classList.add('open');
      return;
    }

    const grouped = {};
    data.results.forEach(item => {
      if (!grouped[item.source]) grouped[item.source] = [];
      grouped[item.source].push(item);
    });

    const sources   = Object.keys(grouped);
    let itemIndex   = 0;

    sources.forEach((source, si) => {
      const label       = document.createElement('div');
      label.className   = 'ls-group-label';
      label.textContent = CONFIG.LABELS[source] || source;
      dropdown.appendChild(label);

      grouped[source].forEach(item => {
        let href = CONFIG.URLS[source] || '#';
        href     = href.replace('{id}', item.id);
        if (item.slug) href = href.replace('{slug}', item.slug);

        const a       = document.createElement('a');
        a.className   = 'ls-item';
        a.href        = href;
        a.setAttribute('role', 'option');
        a.style.animationDelay = (itemIndex * 40) + 'ms';

        a.innerHTML =
          '<div class="ls-title">' + hilite(item.title, q) + '</div>' +
          (item.description
            ? '<div class="ls-desc">' + hilite(trunc(item.description, 90), q) + '</div>'
            : '');

        dropdown.appendChild(a);
        itemIndex++;
      });

      if (si < sources.length - 1) {
        const div     = document.createElement('div');
        div.className = 'ls-divider';
        dropdown.appendChild(div);
      }
    });

    const footer       = document.createElement('div');
    footer.className   = 'ls-footer';
    footer.textContent = data.total + ' hasil ditemukan';
    dropdown.appendChild(footer);

    dropdown.classList.add('open');
  }

  function onKeydown(e) {
    const items   = [...dropdown.querySelectorAll('.ls-item')];
    const focused = dropdown.querySelector('.ls-item.focused');
    let idx       = items.indexOf(focused);

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      moveFocus(items, idx + 1 < items.length ? idx + 1 : 0);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      moveFocus(items, idx - 1 >= 0 ? idx - 1 : items.length - 1);
    } else if (e.key === 'Enter' && focused) {
      e.preventDefault();
      focused.click();
    }
  }

  function moveFocus(items, idx) {
    items.forEach(el => el.classList.remove('focused'));
    if (items[idx]) {
      items[idx].classList.add('focused');
      items[idx].scrollIntoView({ block: 'nearest' });
    }
  }

  function clearDropdown() {
    dropdown.innerHTML = '';
    dropdown.classList.remove('open');
  }

  function showLoading() {
    dropdown.innerHTML =
      '<div class="ls-loading"><span class="ls-spinner"></span> Mencari...</div>';
    dropdown.classList.add('open');
  }

  function showError() {
    dropdown.innerHTML =
      '<div class="ls-error">Terjadi kesalahan, coba lagi.</div>';
    dropdown.classList.add('open');
  }

  function hilite(text, q) {
    if (!q || !text) return esc(text || '');
    const re = new RegExp(
      '(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')',
      'gi'
    );
    return esc(text).replace(re, '<mark>$1</mark>');
  }

  function trunc(str, len) {
    return str && str.length > len ? str.slice(0, len) + '…' : str;
  }

  function esc(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();