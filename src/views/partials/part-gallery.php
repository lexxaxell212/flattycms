<section class="container">
  <div class="poi-gallery-header mb-4">
    <div class="poi-gallery-title-wrap">
      <span class="text-eyebrow">Dari Komunitas</span>
      <h2 class="text-sub-hero">Galeri Foto</h2>
    </div>
    <a href="<?= BASE_URL ?>/gallery" class="poi-gallery-see-all">
      Lihat Semua
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
      </svg>
    </a>
  </div>

  <div class="poi-gallery-masonry" id="poiGalleryGrid">
    <?php for ($i = 0; $i < 6; $i++): ?>
      <div class="poi-gallery-card poi-gallery-skeleton"></div>
    <?php endfor; ?>
  </div>
</section>

<style>

.poi-gallery-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1rem;
}

.poi-gallery-title-wrap {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.poi-gallery-eyebrow {
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--color-primary, #7c3aed);
  opacity: 0.8;
}

.poi-gallery-title {
  font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 700;
  margin: 0;
  color: var(--color-heading, #1e1b4b);
  line-height: 1.15;
}

.poi-gallery-see-all {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--color-primary, #7c3aed);
  text-decoration: none;
  white-space: nowrap;
  padding: 0.45rem 1rem;
  border: 1.5px solid var(--color-primary, #7c3aed);
  border-radius: 999px;
  transition: background 0.2s, color 0.2s;
}

.poi-gallery-see-all:hover {
  background: var(--color-primary, #7c3aed);
  color: #fff;
}

.poi-gallery-see-all svg {
  transition: transform 0.2s;
}

.poi-gallery-see-all:hover svg {
  transform: translateX(3px);
}

/* ── Masonry ── */
.poi-gallery-masonry {
  columns: 3;
  column-gap: 0.875rem;
}

@media (max-width: 768px) {
  .poi-gallery-masonry {
    columns: 2;
  }
}

@media (max-width: 480px) {
  .poi-gallery-masonry {
    columns: 1;
  }
}

/* ── Card ── */
.poi-gallery-card {
  break-inside: avoid;
  margin-bottom: 0.875rem;
  border-radius: 14px;
  overflow: hidden;
  position: relative;
  cursor: default;
  background: #e5e7eb;
  display: block;
}

.poi-gallery-card img {
  width: 100%;
  display: block;
  border-radius: 14px;
  object-fit: cover;
  transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* ── Hover overlay ── */
.poi-gallery-overlay {
  position: absolute;
  inset: 0;
  border-radius: 14px;
  background: linear-gradient(
    to top,
    rgba(15, 10, 40, 0.82) 0%,
    rgba(15, 10, 40, 0.3) 50%,
    transparent 100%
  );
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 1rem;
  gap: 0.35rem;
}

.poi-gallery-card:hover img {
  transform: scale(1.04);
}

.poi-gallery-poi-name {
  font-size: 0.85rem;
  font-weight: 700;
  color: #fff;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.poi-gallery-caption {
  font-size: 0.73rem;
  color: rgba(255,255,255,0.75);
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.poi-gallery-uploader {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  margin-top: 0.25rem;
}

.poi-gallery-avatar {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  object-fit: cover;
  border: 1.5px solid rgba(255,255,255,0.4);
  flex-shrink: 0;
}

.poi-gallery-avatar-initials {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: var(--color-primary, #7c3aed);
  color: #fff;
  font-size: 0.6rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  border: 1.5px solid rgba(255,255,255,0.4);
}

.poi-gallery-uploader-name {
  font-size: 0.7rem;
  color: rgba(255,255,255,0.7);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ── Skeleton ── */
.poi-gallery-skeleton {
  animation: poi-gallery-pulse 1.6s ease-in-out infinite;
  border-radius: 14px;
}

.poi-gallery-skeleton:nth-child(1) { height: 220px; }
.poi-gallery-skeleton:nth-child(2) { height: 160px; }
.poi-gallery-skeleton:nth-child(3) { height: 260px; }
.poi-gallery-skeleton:nth-child(4) { height: 180px; }
.poi-gallery-skeleton:nth-child(5) { height: 240px; }
.poi-gallery-skeleton:nth-child(6) { height: 150px; }

@keyframes poi-gallery-pulse {
  0%, 100% { background: #e5e7eb; }
  50%       { background: #d1d5db; }
}

/* ── Empty state ── */
.poi-gallery-empty {
  grid-column: 1 / -1;
  text-align: center;
  padding: 3rem 1rem;
  color: #9ca3af;
  font-size: 0.9rem;
}
</style>

<script>
(function () {
  const grid   = document.getElementById('poiGalleryGrid');
  const apiUrl = (typeof BASE !== 'undefined' ? BASE : '') + '/api/map/api-gallery.php?page=1';

  function initials(name) {
    return name
      .trim()
      .split(' ')
      .slice(0, 2)
      .map(w => w[0])
      .join('')
      .toUpperCase();
  }

  function avatarEl(photo) {
    if (photo.uploader_avatar) {
      const img = document.createElement('img');
      img.src       = (typeof BASE !== 'undefined' ? BASE : '') + '/uploads/' + photo.uploader_avatar;
      img.alt       = photo.uploader_name;
      img.className = 'poi-gallery-avatar';
      img.onerror   = function () {
        this.replaceWith(initialsEl(photo.uploader_name));
      };
      return img;
    }
    return initialsEl(photo.uploader_name);
  }

  function initialsEl(name) {
    const div = document.createElement('div');
    div.className   = 'poi-gallery-avatar-initials';
    div.textContent = initials(name);
    return div;
  }

  function buildCard(photo) {
    const uploadBase = (typeof BASE !== 'undefined' ? BASE : '') + '/uploads/';

    const card = document.createElement('div');
    card.className = 'poi-gallery-card';

    const img = document.createElement('img');
    img.src     = uploadBase + photo.photo_path;
    img.alt     = photo.poi_name;
    img.loading = 'lazy';
    img.onerror = function () {
      card.style.display = 'none';
    };

    const overlay = document.createElement('div');
    overlay.className = 'poi-gallery-overlay';

    const poiName = document.createElement('div');
    poiName.className   = 'poi-gallery-poi-name';
    poiName.textContent = photo.poi_name;

    overlay.appendChild(poiName);

    if (photo.caption) {
      const cap = document.createElement('div');
      cap.className   = 'poi-gallery-caption';
      cap.textContent = photo.caption;
      overlay.appendChild(cap);
    }

    const uploader = document.createElement('div');
    uploader.className = 'poi-gallery-uploader';

    const uName = document.createElement('span');
    uName.className   = 'poi-gallery-uploader-name';
    uName.textContent = photo.uploader_name;

    uploader.appendChild(avatarEl(photo));
    uploader.appendChild(uName);
    overlay.appendChild(uploader);

    card.appendChild(img);
    card.appendChild(overlay);

    return card;
  }

  fetch(apiUrl)
    .then(r => r.json())
    .then(res => {
      grid.innerHTML = '';

      if (!res.success || !res.data || res.data.length === 0) {
        const empty = document.createElement('div');
        empty.className   = 'poi-gallery-empty';
        empty.textContent = 'Belum ada foto yang diunggah.';
        grid.appendChild(empty);
        return;
      }

      const photos = res.data.slice(0, 6);
      photos.forEach(photo => grid.appendChild(buildCard(photo)));
    })
    .catch(() => {
      grid.innerHTML = '';
    });
})();
</script>
