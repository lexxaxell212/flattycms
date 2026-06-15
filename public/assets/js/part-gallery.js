(function () {
  const grid = document.getElementById('poiGalleryGrid');
  const apiUrl = (typeof BASE !== 'undefined' ? BASE: '') + '/api/map/api-gallery.php?page=1';
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
      img.src = photo.uploader_avatar;
      img.alt = photo.uploader_name;
      img.className = 'poi-gallery-avatar';
      img.onerror = function () {
        this.replaceWith(initialsEl(photo.uploader_name));
      };
      return img;
    }
    return initialsEl(photo.uploader_name);
  }
  function initialsEl(name) {
    const div = document.createElement('div');
    div.className = 'poi-gallery-avatar-initials';
    div.textContent = initials(name);
    return div;
  }
  function buildCard(photo) {
    const uploadBase = (typeof BASE !== 'undefined' ? BASE: '') + '/uploads/';

    const card = document.createElement('div');
    card.className = 'poi-gallery-card';

    const img = document.createElement('img');
    img.src = uploadBase + photo.photo_path;
    img.alt = photo.poi_name;
    img.loading = 'lazy';
    img.onerror = function () {
      card.style.display = 'none';
    };

    const overlay = document.createElement('div');
    overlay.className = 'poi-gallery-overlay';

    const poiName = document.createElement('div');
    poiName.className = 'poi-gallery-poi-name';
    poiName.textContent = photo.poi_name;

    overlay.appendChild(poiName);

    if (photo.caption) {
      const cap = document.createElement('div');
      cap.className = 'poi-gallery-caption';
      cap.textContent = photo.caption;
      overlay.appendChild(cap);
    }

    const uploader = document.createElement('div');
    uploader.className = 'poi-gallery-uploader';

    const uName = document.createElement('span');
    uName.className = 'poi-gallery-uploader-name';
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
      empty.className = 'poi-gallery-empty';
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