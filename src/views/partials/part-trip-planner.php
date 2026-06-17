<div class="container">
  <section id="trip-pl" class="tp-overview">
    <div class="tp-overview__left">
      <div class="tp-svg-stage">
        <svg class="tp-svg-map" viewBox="0 0 420 420" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <radialGradient id="glow1" cx="50%" cy="50%" r="50%">
              <stop offset="0%" stop-color="#a78bfa" stop-opacity="0.18" />
              <stop offset="100%" stop-color="#a78bfa" stop-opacity="0" />
            </radialGradient>
            <radialGradient id="glow2" cx="50%" cy="50%" r="50%">
              <stop offset="0%" stop-color="#818cf8" stop-opacity="0.13" />
              <stop offset="100%" stop-color="#818cf8" stop-opacity="0" />
            </radialGradient>
            <filter id="blur1">
              <feGaussianBlur stdDeviation="18" />
            </filter>
            <marker id="arrowhead" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
              <path d="M0,0 L0,6 L6,3 z" fill="#a78bfa" opacity="0.6" />
            </marker>
          </defs>
          <ellipse cx="140" cy="200" rx="110" ry="110" fill="url(#glow1)" filter="url(#blur1)" />
          <ellipse cx="290" cy="230" rx="90" ry="90" fill="url(#glow2)" filter="url(#blur1)" />
          <g class="tp-grid" opacity="0.07" stroke="#7c3aed" stroke-width="1">
            <line x1="0" y1="70" x2="420" y2="70" />
            <line x1="0" y1="140" x2="420" y2="140" />
            <line x1="0" y1="210" x2="420" y2="210" />
            <line x1="0" y1="280" x2="420" y2="280" />
            <line x1="0" y1="350" x2="420" y2="350" />
            <line x1="70" y1="0" x2="70" y2="420" />
            <line x1="140" y1="0" x2="140" y2="420" />
            <line x1="210" y1="0" x2="210" y2="420" />
            <line x1="280" y1="0" x2="280" y2="420" />
            <line x1="350" y1="0" x2="350" y2="420" />
          </g>

          <path class="tp-route-path"
            d="M 110 310 C 130 270, 160 260, 200 240 C 240 220, 260 200, 280 170 C 300 145, 310 130, 300 110"
            stroke="#a78bfa" stroke-width="2.5" stroke-dasharray="6 5"
            fill="none" opacity="0.7" marker-end="url(#arrowhead)" />
          <path class="tp-route-path-2"
            d="M 200 240 C 230 250, 260 260, 290 270"
            stroke="#818cf8" stroke-width="1.5" stroke-dasharray="4 4"
            fill="none" opacity="0.5" />
          <g class="tp-node tp-node--a">
            <circle cx="110" cy="310" r="28" fill="#7c3aed" opacity="0.08" />
            <circle cx="110" cy="310" r="16" fill="#7c3aed" opacity="0.15" />
            <circle cx="110" cy="310" r="8" fill="#7c3aed" />
            <circle cx="110" cy="310" r="4" fill="#fff" />
            <circle class="tp-pulse" cx="110" cy="310" r="8" fill="none" stroke="#7c3aed" stroke-width="1.5" />
          </g>
          <g class="tp-node tp-node--b">
            <circle cx="200" cy="240" r="22" fill="#818cf8" opacity="0.1" />
            <circle cx="200" cy="240" r="12" fill="#818cf8" opacity="0.18" />
            <circle cx="200" cy="240" r="6" fill="#818cf8" />
            <circle cx="200" cy="240" r="3" fill="#fff" />
          </g>
          <g class="tp-node tp-node--c">
            <circle cx="300" cy="110" r="32" fill="#6d28d9" opacity="0.1" />
            <circle cx="300" cy="110" r="20" fill="#6d28d9" opacity="0.15" />
            <path d="M300 85 C288 85, 278 95, 278 107 C278 122, 300 140, 300 140 C300 140, 322 122, 322 107 C322 95, 312 85, 300 85Z"
              fill="#6d28d9" />
            <circle cx="300" cy="107" r="7" fill="#fff" />
            <circle class="tp-pulse tp-pulse--c" cx="300" cy="107" r="14" fill="none" stroke="#6d28d9" stroke-width="1.5" />
          </g>
          <g class="tp-node tp-node--d">
            <circle cx="290" cy="270" r="18" fill="#a78bfa" opacity="0.1" />
            <circle cx="290" cy="270" r="10" fill="#a78bfa" opacity="0.2" />
            <circle cx="290" cy="270" r="5" fill="#a78bfa" />
            <circle cx="290" cy="270" r="2.5" fill="#fff" />
          </g>
          <g class="tp-label tp-label--dist">
            <rect x="148" y="218" width="68" height="22" rx="11" fill="#1e1b4b" opacity="0.85" />
            <text x="182" y="233" text-anchor="middle" fill="#a78bfa" font-size="10" font-family="monospace" font-weight="600">12.4 km</text>
          </g>
          <g class="tp-chip tp-chip--1">
            <rect x="32" y="160" width="72" height="26" rx="13" fill="#fff" opacity="0.92" />
            <rect x="32" y="160" width="72" height="26" rx="13" stroke="#ede9fe" stroke-width="1" />
            <circle cx="50" cy="173" r="6" fill="#7c3aed" opacity="0.8" />
            <text x="62" y="177" fill="#4c1d95" font-size="9.5" font-family="monospace" font-weight="600">Wisata</text>
          </g>
          <g class="tp-chip tp-chip--2">
            <rect x="310" y="185" width="78" height="26" rx="13" fill="#fff" opacity="0.92" />
            <rect x="310" y="185" width="78" height="26" rx="13" stroke="#ede9fe" stroke-width="1" />
            <circle cx="328" cy="198" r="6" fill="#818cf8" opacity="0.8" />
            <text x="340" y="202" fill="#4c1d95" font-size="9.5" font-family="monospace" font-weight="600">Kuliner</text>
          </g>
          <g class="tp-chip tp-chip--3">
            <rect x="55" y="340" width="68" height="26" rx="13" fill="#fff" opacity="0.92" />
            <rect x="55" y="340" width="68" height="26" rx="13" stroke="#ede9fe" stroke-width="1" />
            <circle cx="73" cy="353" r="6" fill="#a78bfa" opacity="0.8" />
            <text x="85" y="357" fill="#4c1d95" font-size="9.5" font-family="monospace" font-weight="600">Hotel</text>
          </g>
        </svg>
      </div>
    </div>
    <div class="tp-overview__right">
      <div class="tp-overview__content">
        <span class="text-eyebrow" data-bhs="tp.eyebrow">
          Trip Planner
        </span>
        <h2 data-bhs="tp.title">
          Bikin Rencana Liburanmu di Bandung
        </h2>
        <p data-bhs="tp.excerpt">
          Dari kuliner legendaris hingga wisata alam tersembunyi, susun agenda liburan terbaikmu di Bandung dengan mudah.
        </p>
        <ul class="tp-feature-list">
          <li class="text-muted">
            <span class="tp-feature-icon">
              <i class="fas fa-location-dot"></i>
            </span>
            <span data-bhs="tp.li.1">Jelajahi ratusan tempat hits di Bandung</span>
          </li>
          <li class="text-muted">
            <span class="tp-feature-icon">
              <i class="fas fa-route"></i>
            </span>
            <span data-bhs="tp.li.2">Atur & simpan rute sesukamu</span>
          </li>
          <li class="text-muted">
            <span class="tp-feature-icon">
              <i class="fas fa-map"></i>
            </span>
            <span data-bhs="tp.li.3">Peta POI Bandung</span>
          </li>
        </ul>
        <div class="tp-overview__cta">
          <a href="/trip" class="btn btn-primary">
            <span data-bhs="btn.tp.2">Mulai Bikin Rute</span>
            <i class="arrow-icon fas fa-angle-right ms-1"></i>
          </a>
        </div>
      </div>

    </div>
  </section>
</div>