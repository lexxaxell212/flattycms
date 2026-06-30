(function () {
  const API_GAL = BASE + "/api/map/api-gallery.php";
  const API_REV = BASE + "/api/map/api-review.php";

  function formatDate(str) {
    return new Date(str).toLocaleDateString('id-ID', {
      day: 'numeric', month: 'short', year: 'numeric'
    });
  }

  function stars(rating) {
    return Array.from({ length: 5 }, (_, i) =>
      `<i class="fa-${i < rating ? 'solid' : 'regular'} fa-star"></i>`
    ).join('');
  }

  async function loadPoiGallery() {
    const wrap = document.getElementById('poiGalleryGrid');
    try {
      const res = await fetch(`${API_GAL}?poi_id=${POI_ID_CURRENT}&page=1`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const json = await res.json();
      const photos = (json.data || []).slice(0, 5);

      if (!photos.length) {
        wrap.innerHTML = `<div class="col-12 text-center text-muted py-3">
          <i class="fa-solid fa-image fa-2x mb-2 d-block opacity-25 mx-auto"></i>
          Belum ada foto untuk lokasi ini.
        </div>`;
        return;
      }

      wrap.innerHTML = photos.map(p => `
        <div class="col-6 col-md-4 col-lg-2dot4">
        <div class="card card-flatty h-100">
        <div class="card-body p-2">
        <div class="position-relative" style="padding-top:100%">
        <img src="${BASE}/uploads/${p.photo_path}" class="position-absolute top-0 start-0 w-100 h-100 card-img" style="object-fit:cover" loading="lazy" onerror="this.src='${BASE}/uploads/default.jpg'">
        </div>
        </div>
        <div class="card-footer p-2">
        <div class="text-muted small text-truncate">${p.uploader_name}</div>
        </div>
        </div>
        </div>
      `).join('');
    } catch (e) {
      wrap.innerHTML = `<div class="col-12 text-center text-muted py-3">Gagal memuat foto.</div>`;
    }
  }

  async function loadPoiReviews() {
    const wrap = document.getElementById('poiReviewGrid');
    try {
      const res = await fetch(`${API_REV}?poi_id=${POI_ID_CURRENT}&page=1`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const json = await res.json();
      const reviews = (json.data || []).slice(0, 5);

      if (!reviews.length) {
        wrap.innerHTML = `<div class="text-center text-muted py-3">
          <i class="fa-solid fa-star fa-2x mb-2 d-block opacity-25 mx-auto"></i>
          Belum ada review untuk lokasi ini.
        </div>`;
        return;
      }

      wrap.innerHTML = reviews.map(r => `
        <div class="gal-review-card">
        <div class="gal-review-card__header">
        <div class="gal-review-card__user">
        <img src="${r.avatar || BASE+'/uploads/default.jpg'}" class="gal-review-card__avatar" onerror="this.src='${BASE}/uploads/default.jpg'">
        <div>
        <div class="gal-review-card__name">${r.user_name}</div>
        </div>
        </div>
        <div class="text-end">
        <div class="gal-review-card__stars">${stars(r.rating)}</div>
        <div class="gal-review-card__date">${formatDate(r.created_at)}</div>
        </div>
        </div>
        ${r.judul ? `<div class="gal-review-card__title">${r.judul}</div>` : ''}
        <div class="gal-review-card__body">${r.cerita}</div>
        </div>
      `).join('');
    } catch (e) {
      wrap.innerHTML = `<div class="text-center text-muted py-3">Gagal memuat review.</div>`;
    }
  }

  loadPoiGallery();
  loadPoiReviews();
})();