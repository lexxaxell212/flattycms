document.addEventListener("DOMContentLoaded", () => {
  const hero = document.querySelector(".tp-main-outer-content");
  if (hero) {
    window.addEventListener("scroll", () => {
      hero.style.transform = `translateY(${window.scrollY * 0.25}px)`;
    });
  }
});

(function () {
  let startPoint = null;
  let routes = [];
  let routeLine = null;
  let markers = {};
  let activeCat = "";
  let routePolyline = null;
  let routeDuration = 0;
  let routeGenerated = false; // FIX: flag untuk disable simpan sebelum generate

  const routeEmptyHTML = `<div id="routeEmpty" style="display:flex">
  <div class="route-empty">
    <i class="fa-solid fa-map-pin text-accent"></i>
    <span data-bhs="tp.page.map.route_empty">Pilih titik awal - Klik pin di map untuk tambah lokasi</span>
  </div>
</div>`;

  const map = L.map("mainMap").setView([-6.9175, 107.6191], 13);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap",
  }).addTo(map);
  window.mainMap = map;

  const iconColors = {
    1: "oklch(0.487 0.127 297)",
    2: "oklch(0.769 0.156 295)",
    3: "oklch(0.558 0.174 293)",
  };
  function makeIcon(cat_id) {
    const c = iconColors[cat_id] || "oklch(0.487 0.167 295)";
    return L.divIcon({
      className: "",
      html: `<div style="width:32px;height:32px;border-radius:50% 50% 50% 0;background:${c};transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.35)"></div>`,
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -34],
    });
  }

  function renderMarkers() {
    Object.values(markers).forEach((m) => map.removeLayer(m));
    markers = {};
    POIS.forEach((poi) => {
      if (activeCat && poi.category_id != activeCat) return;
      const m = L.marker([poi.latitude, poi.longitude], {
        icon: makeIcon(poi.category_id),
      })
        .addTo(map)
        .bindPopup(buildPopup(poi), {
          maxWidth: 240,
        });
      markers[poi.id] = m;
    });
  }

  function buildPopup(poi) {
    const inRoute = routes.some((r) => r.poi_id == poi.id);
    const uploadBtn = IS_LOGGED
      ? `<button onclick="openUpload(${poi.id}, '${poi.name.replace(
          /'/g,
          "&#39;",
        )}')" style="background:oklch(0.623 0.214 231);color:#fff;border:none;border-radius:.4rem;padding:.3rem .6rem;font-size:.75rem;cursor:pointer"><i class="fa-solid fa-camera"></i></button>`
      : "";
    return `
    <div style="min-width:200px">
    <div style="font-weight:700;font-size:.9rem;margin-bottom:.2rem">${poi.name}</div>
    <div style="font-size:.75rem;color:oklch(0.641 0.156 295);margin-bottom:.3rem">${poi.category_name}</div>
    ${poi.address ? `<div style="font-size:.72rem;color:oklch(0.553 0.016 264);margin-bottom:.6rem"><i class="fa-solid fa-road" style="margin-right:.25rem"></i>${poi.address}</div>` : ""}
    <div style="display:flex;gap:.4rem;flex-wrap:wrap">
    <button onclick="addToRoute(${poi.id})" style="flex:1;background:${inRoute ? "oklch(0.527 0.154 155)" : "oklch(0.487 0.167 295)"};color:#fff;border:none;border-radius:.4rem;padding:.3rem .6rem;font-size:.75rem;font-weight:600;cursor:pointer">
    ${inRoute ? '<i class="fa-solid fa-check"></i> Ditambahkan' : '<i class="fa-solid fa-plus"></i> Tambah Rute'}
    </button>
    ${uploadBtn}
    </div>
    </div>`;
  }

  renderMarkers();

  document.querySelectorAll(".cat-filter").forEach((btn) => {
    btn.addEventListener("click", function () {
      document.querySelectorAll(".cat-filter").forEach((b) => {
        b.classList.remove("active");
      });
      this.classList.add("active");
      activeCat = this.dataset.cat;
      renderMarkers();
    });
  });

  document.getElementById("searchPoi").addEventListener("input", function () {
    const q = this.value.toLowerCase();
    const box = document.getElementById("searchPoiResults");
    box.innerHTML = "";
    if (!q) {
      box.style.display = "none";
      return;
    }
    const matches = POIS.filter((p) => p.name.toLowerCase().includes(q)).slice(
      0,
      6,
    );
    box.style.display = "block";
    if (!matches.length) {
      box.innerHTML = '<div class="small text-muted">Tidak ditemukan</div>';
      return;
    }
    matches.forEach((p) => {
      const el = document.createElement("button");
      el.type = "button";
      el.className = "btn-popup";
      el.innerHTML = `<span>${p.name}</span> • <span class="text-muted small">${p.category_name || ""}</span>`;
      el.addEventListener("click", () => {
        box.style.display = "none";
        document.getElementById("searchPoi").value = "";
        const marker = markers[p.id];
        if (marker) {
          map.flyTo([p.latitude, p.longitude], 16, {
            duration: 1,
          });
          setTimeout(() => marker.openPopup(), 1000);
        }
      });
      box.appendChild(el);
    });
  });

  document.addEventListener("click", (e) => {
    if (
      !e.target.closest("#searchPoi") &&
      !e.target.closest("#searchPoiResults")
    )
      document.getElementById("searchPoiResults").style.display = "none";
  });

  document
    .getElementById("btnResetPoiSearch")
    .addEventListener("click", function () {
      document.getElementById("searchPoi").value = "";
      document.getElementById("searchPoiResults").innerHTML = "";
      document.getElementById("searchPoiResults").style.display = "none";
    });

  function searchStartPoint(q) {
    const el = document.getElementById("startResults");
    if (!q) {
      el.style.display = "none";
      return;
    }
    const matches = POIS.filter((p) =>
      p.name.toLowerCase().includes(q.toLowerCase()),
    ).slice(0, 6);
    el.innerHTML = "";
    el.style.display = "";
    if (!matches.length) {
      el.innerHTML = '<div class="small text-muted">Tidak ditemukan</div>';
      return;
    }
    matches.forEach((p) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "btn-popup";
      btn.innerHTML = `
      <span>${p.name}</span> • <span class="text-muted small">${p.category_name || ""}</span>
      `;
      btn.addEventListener("click", () => {
        startPoint = {
          name: p.name,
          lat: parseFloat(p.latitude),
          lng: parseFloat(p.longitude),
          poi_image: p.poi_image || null,
          description: p.description || "",
          slug: p.slug || null,
        };
        document.getElementById("startName").textContent = startPoint.name;
        document.getElementById("startName2").textContent = startPoint.name;
        document.getElementById("startDesc").textContent =
          startPoint.description || "Deskripsi belum tersedia.";
        document.getElementById("startImg").innerHTML = startPoint.poi_image
          ? `<img src="${escHtml(startPoint.poi_image)}" class="card-img-top" onerror="this.src='/uploads/poi-placeholder.jpg'">`
          : `<img src="/uploads/poi-placeholder.jpg" class="card-img-top">`;
        document.getElementById("startSelected").style.display = "";
        document.getElementById("startInput").value = "";
        el.style.display = "none";
        // FIX: reset routeGenerated saat ganti titik awal
        routeGenerated = false;
        updatePlannerUI();
      });
      el.appendChild(btn);
    });
  }

  document.getElementById("startInput").addEventListener("input", function () {
    searchStartPoint(this.value.trim());
  });
  document.getElementById("startInput").addEventListener("keydown", (e) => {
    if (e.key === "Escape")
      document.getElementById("startResults").style.display = "none";
  });

  document
    .getElementById("btnResetStartSearch")
    .addEventListener("click", function () {
      document.getElementById("startInput").value = "";
      document.getElementById("startResults").innerHTML = "";
      document.getElementById("startResults").style.display = "none";
    });

  window.addToRoute = function (poi_id) {
    window.isLoadedTrip = false;
    if (!startPoint) {
      flattyToast("warning", "Pilih titik awal dulu");
      return;
    }
    if (routes.some((r) => r.poi_id == poi_id)) {
      flattyToast("info", "Sudah ada di rute");
      return;
    }
    const poi = POIS.find((p) => p.id == poi_id);
    if (!poi) return;
    routes.push({
      poi_id: poi.id,
      name: poi.name,
      lat: parseFloat(poi.latitude),
      lng: parseFloat(poi.longitude),
      note: "",
      poi_image: poi.poi_image || null,
      description: poi.description || "",
      slug: poi.slug || null,
    });
    map.closePopup();
    // FIX: reset routeGenerated saat ada perubahan rute
    routeGenerated = false;
    updatePlannerUI();
    updateRouteOnMap();
    // FIX: toast berhasil add route
    flattyToast("success", `${poi.name} ditambahkan ke rute!`);
  };

  function updatePlannerUI() {
    const list = document.getElementById("routeList");
    const btnG = document.getElementById("btnGenerateRoute");
    const btnS = document.getElementById("btnSaveTrip");
    const distI = document.getElementById("distanceInfo");

    if (routes.length === 0) {
      list.innerHTML = routeEmptyHTML;
      btnG.disabled = true;
      if (btnS) btnS.disabled = true;
      distI.style.display = "none";
      return;
    }

    list.innerHTML = routes
      .map(
        (r, i) => `
      <div class="d-flex align-items-start gap-2 mb-2" data-idx="${i}">
      <div class="flex-grow-1 min-w-0">
      <div class="small fw-semibold text-truncate mb-2">
      <span class="text-purple">
      ${i + 1}
      </span>
      • ${r.name}
      </div>
      ${r.distance_from_prev ? `<div class="text-muted mb-2" style="font-size:.7rem"><i class="fa-solid fa-ruler me-1"></i>${r.distance_from_prev} km dari titik sebelumnya</div>` : ""}
      <div class="card card-glass mb-2">
      <button style="z-index:10" class="btn btn-danger btn-fit btn-remove-route position-absolute top-0 end-0 m-4" data-idx="${i}">
      <i class="fa-solid fa-xmark"></i>
      </button>
      ${
        r.poi_image
          ? `<img src="${escHtml(r.poi_image)}" class="card-img-top" onerror="this.src='/uploads/poi-placeholder.jpg'">`
          : `<img src="/uploads/poi-placeholder.jpg" class="card-img-top">`
      }
      <div class="card-body">
      <h5 class="mb-2">${escHtml(r.name)}</h5>
      <p class="text-muted small">${escHtml(r.description || "Deskripsi belum tersedia.")}</p>
      </div>
      </div>
      ${IS_LOGGED ? `<div class="mt-2"><input type="text" class="form-control note-input" data-idx="${i}" placeholder="Tambah catatan untuk POI ini..." value="${escHtml(r.note)}" style="font-size:.9rem" ${window.isLoadedTrip ? "disabled" : ""}></div>` : ""}
      </div>
      </div>`,
      )
      .join("");

    list.querySelectorAll(".note-input").forEach((inp) => {
      inp.addEventListener("input", function () {
        routes[this.dataset.idx].note = this.value;
      });
    });
    list.querySelectorAll(".btn-remove-route").forEach((btn) => {
      btn.addEventListener("click", function () {
        routes.splice(parseInt(this.dataset.idx), 1);
        routeGenerated = false;
        updatePlannerUI();
        updateRouteOnMap();
      });
    });

    btnG.disabled = !(startPoint && routes.length > 0);
    if (btnS)
      btnS.disabled = !(routeGenerated && startPoint && routes.length > 0);

    const total = routes.reduce(
      (s, r) => s + (parseFloat(r.distance_from_prev) || 0),
      0,
    );
    if (total > 0) {
      document.getElementById("totalDist").textContent = total.toFixed(1);
      document.getElementById("totalStops").textContent =
        `· ${routes.length} lokasi`;
      distI.style.display = "";
    }
  }
  
  document
    .getElementById("btnGenerateRoute")
    .addEventListener("click", async () => {
      if (!startPoint || routes.length === 0) return;
      const points = [
        [startPoint.lat, startPoint.lng],
        ...routes.map((r) => [r.lat, r.lng]),
      ];
      const fd = new FormData();
      fd.append("coordinates", JSON.stringify(points));
      const btn = document.getElementById("btnGenerateRoute");
      btn.innerHTML =
        '<div class="btn-fetch"><span></span><span></span><span></span></div>';
      btn.disabled = true;
      try {
        await new Promise((r) => setTimeout(r, 1000));
        const res = await fetch(`${BASE}/api/map/api-route.php`, {
          method: "POST",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          body: fd,
        });
        const data = await res.json();
        if (data.success) {
          routePolyline = data.polyline;
          routeDuration = data.duration;
          // FIX: set flag routeGenerated = true setelah berhasil
          routeGenerated = true;
          updateRouteOnMap(routePolyline);
          document.getElementById("totalDist").textContent = data.distance;
          document.getElementById("totalStops").textContent =
            `· ${routes.length} lokasi · ~${data.duration} menit`;
          document.getElementById("distanceInfo").style.display = "";
          // FIX: update btnSaveTrip setelah generate berhasil
          const btnS = document.getElementById("btnSaveTrip");
          if (btnS) btnS.disabled = false;
          flattyToast("success", "Rute berhasil dibuat!");
        } else {
          flattyToast("error", data.message ?? "Gagal membuat rute.");
        }
      } catch (e) {
        flattyToast("error", "Tidak bisa buat rute.");
      } finally {
        btn.innerHTML = "Buat Trip";
        btn.disabled = false;
      }
    });

  function updateRouteOnMap(points) {
    if (routeLine) map.removeLayer(routeLine);
    if (!points || points.length < 2) return;
    routeLine = L.polyline(points, {
      color: "#9061f9",
      weight: 4,
      opacity: 0.85,
    }).addTo(map);
    map.fitBounds(routeLine.getBounds(), {
      padding: [40, 40],
    });
  }

  document.getElementById("btnResetTrip").addEventListener("click", () => {
    flattyConfirm("Reset rute? Semua titik akan dihapus.", () => {
      startPoint = null;
      routes = [];
      routePolyline = null;
      routeDuration = 0;
      window.isLoadedTrip = false;
      // FIX: reset flag
      routeGenerated = false;
      document.getElementById("startSelected").style.display = "none";
      document.getElementById("startInput").value = "";
      if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
      }
      updatePlannerUI();
      renderMarkers();
    });
  });

  if (IS_LOGGED) {
    document.getElementById("btnSaveTrip").addEventListener("click", () => {
      const sf = document.getElementById("saveForm");
      sf.style.display = sf.style.display === "none" ? "" : "none";
    });

    document
      .getElementById("tripTitle")
      .addEventListener("keydown", async (e) => {
        if (e.key === "Enter") await doSaveTrip();
      });
    document
      .getElementById("btnConfirmSave")
      .addEventListener("click", doSaveTrip);

    async function doSaveTrip() {
      const title =
        document.getElementById("tripTitle").value.trim() || "Trip Bandungku";

      const existingNames = routes.map((r) => r.name.toLowerCase());
      if (existingNames.includes(startPoint.name.toLowerCase())) {
        flattyToast(
          "warning",
          "Nama tidak boleh sama, gunakan nama yang berbeda",
        );
        return;
      }

      const fd = new FormData();
      fd.append("action", "save");
      fd.append("csrf_token", CSRF);
      fd.append("title", title);
      fd.append("start_point_name", startPoint.name);
      fd.append("start_lat", startPoint.lat);
      fd.append("start_lng", startPoint.lng);
      fd.append(
        "route_polyline",
        routePolyline ? JSON.stringify(routePolyline) : "",
      );
      fd.append("duration", routeDuration ?? 0);
      fd.append(
        "items",
        JSON.stringify(
          routes.map((r, i) => ({
            poi_id: r.poi_id,
            order_index: i + 1,
            distance_from_prev: r.distance_from_prev || 0,
            note: r.note,
          })),
        ),
      );
      const btn = document.getElementById("btnConfirmSave");
      btn.innerHTML =
        '<div class="btn-fetch"><span></span><span></span><span></span></div>';
      btn.disabled = true;
      try {
        await new Promise((r) => setTimeout(r, 1000));
        const res = await fetch(API_TRIP, {
          method: "POST",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          body: fd,
        });
        const data = await res.json();
        if (data.success) {
          flattyToast("success", "Rute trip disimpan!");
          document.getElementById("saveForm").style.display = "none";
          if (typeof window.refreshTripku === "function")
            window.refreshTripku();
        } else {
          flattyToast("error", data.message ?? "Gagal menyimpan trip.");
        }
      } catch (e) {
        flattyToast("error", "Tidak bisa menyimpan trip.");
      } finally {
        btn.innerHTML = "Simpan";
        btn.disabled = false;
      }
    }
  }

  window.loadTripById = async function (id) {
    try {
      const res = await fetch(`${API_TRIP}?id=${id}`, {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      });
      const json = await res.json();
      if (!json.success) {
        flattyToast("error", json.message ?? "Gagal memuat trip.");
        return;
      }
      const trip = json.data;
      startPoint = {
        name: trip.start_point_name,
        lat: parseFloat(trip.start_lat),
        lng: parseFloat(trip.start_lng),
      };
      document.getElementById("startName").textContent = startPoint.name;
      document.getElementById("startName2").textContent = startPoint.name;
      document.getElementById("startSelected").style.display = "";
      document.getElementById("startDesc").textContent = "Deskripsi belum tersedia.";
document.getElementById("startImg").innerHTML = `<img src="/uploads/poi-placeholder.jpg" class="card-img-top">`;
      
      routes = trip.items.map((item) => ({
        poi_id: item.poi_id,
        name: item.poi_name,
        lat: parseFloat(item.latitude),
        lng: parseFloat(item.longitude),
        distance_from_prev: item.distance_from_prev,
        note: item.note || "",
        poi_image: item.poi_image || null,
        description: item.description || "",
      }));
      window.isLoadedTrip = true;
      // FIX: set routeGenerated true saat load trip tersimpan (sudah punya rute)
      routeGenerated = true;
      updatePlannerUI();
      if (trip.route_polyline) {
        routePolyline = JSON.parse(trip.route_polyline);
        routeDuration = trip.duration || 0;
        updateRouteOnMap(routePolyline);
        // FIX: total_distance dari trip tersimpan langsung ditampilkan
        document.getElementById("totalDist").textContent =
          trip.total_distance ?? "—";
        document.getElementById("totalStops").textContent =
          `· ${routes.length} lokasi${trip.duration ? " · ~" + trip.duration + " menit" : ""}`;
        document.getElementById("distanceInfo").style.display = "flex";
      } else {
        routePolyline = null;
        updateRouteOnMap([
          [startPoint.lat, startPoint.lng],
          ...routes.map((r) => [r.lat, r.lng]),
        ]);
      }
      setTimeout(() => {
        const mapEl = document.getElementById("mainMap");
        if (mapEl) {
          mapEl.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
          map.invalidateSize();
        }
      }, 150);
      flattyToast("success", `Trip "${trip.title}" dimuat!`);
    } catch (e) {
      flattyToast("error", "Gagal memuat trip.");
    }
  };

  window.deleteTripById = async function (id, title) {
    flattyConfirm(`Hapus trip "${title}"?`, async () => {
      const fd = new FormData();
      fd.append("action", "delete");
      fd.append("csrf_token", CSRF);
      fd.append("trip_id", id);
      const res = await fetch(API_TRIP, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
        body: fd,
      });
      const data = await res.json();
      if (data.success) {
        flattyToast("success", "Trip dihapus!");
        if (typeof window.refreshTripku === "function") window.refreshTripku();
      } else {
        flattyToast("error", data.message ?? "Gagal menghapus trip.");
      }
    });
  };

  if (IS_LOGGED) {
    const uploadModal = document.getElementById("uploadModal");

    window.openUpload = function (poi_id, poi_name) {
      document.getElementById("uploadPoiId").value = poi_id || "";
      document.getElementById("uploadPoiName").textContent = poi_name || "";
      document.getElementById("uploadPoiSelected").style.display =
        poi_id && poi_name ? "" : "none";
      document.getElementById("uploadPoiSearch").value = poi_name || "";
      document.getElementById("uploadPreview").style.display = "none";
      document.getElementById("uploadFile").value = "";
      document.getElementById("uploadCredit").value = "";
      uploadModal.style.display = "flex";
    };

    window.openUploadModal = window.openUpload;

    ["btnBatalUpload", "btnBatalUpload2"].forEach((id) => {
      document.getElementById(id)?.addEventListener("click", () => {
        uploadModal.style.display = "none";
      });
    });

    uploadModal.addEventListener("click", (e) => {
      if (e.target === uploadModal) uploadModal.style.display = "none";
    });

    document
      .getElementById("uploadPoiSearch")
      .addEventListener("input", function () {
        const q = this.value.toLowerCase();
        const box = document.getElementById("uploadPoiResults");
        box.innerHTML = "";
        if (!q) {
          box.style.display = "none";
          return;
        }
        box.style.display = "";
        const matches = POIS.filter((p) =>
          p.name.toLowerCase().includes(q),
        ).slice(0, 6);
        if (!matches.length) {
          box.innerHTML = '<div class="small text-muted">Tidak ditemukan</div>';
          return;
        }
        matches.forEach((p) => {
          const el = document.createElement("button");
          el.type = "button";
          el.className = "btn-popup";
          el.textContent = p.name;
          el.addEventListener("click", () => {
            document.getElementById("uploadPoiId").value = p.id;
            document.getElementById("uploadPoiName").textContent = p.name;
            document.getElementById("uploadPoiSelected").style.display = "";
            box.style.display = "none";
          });
          box.appendChild(el);
        });
      });

    document
      .getElementById("uploadFile")
      .addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 10 * 1024 * 1024) {
          flattyToast("warning", "File terlalu besar, maksimal 10MB.");
          this.value = "";
          return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
          document.getElementById("previewImg").src = e.target.result;
          document.getElementById("uploadPreview").style.display = "";
        };
        reader.readAsDataURL(file);
      });

    document
      .getElementById("btnKirimUpload")
      .addEventListener("click", async () => {
        const poi_id = document.getElementById("uploadPoiId").value;
        const file = document.getElementById("uploadFile").files[0];
        if (!poi_id || !file) {
          flattyToast("warning", "Pilih lokasi dan foto dulu.");
          return;
        }
        const btn = document.getElementById("btnKirimUpload");
        btn.innerHTML =
          '<div class="btn-fetch"><span></span><span></span><span></span></div>';
        btn.disabled = true;
        const fd = new FormData();
        fd.append("csrf_token", CSRF);
        fd.append("poi_id", poi_id);
        fd.append("photo", file);
        fd.append(
          "caption",
          document.getElementById("uploadCredit").value.trim(),
        );
        try {
          await new Promise((r) => setTimeout(r, 1000));
          const res = await fetch(API_GAL, {
            method: "POST",
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
            body: fd,
          });
          const data = await res.json();
          if (data.success) {
            uploadModal.style.display = "none";
            flattyToast("success", "Foto berhasil diupload!");
          } else {
            flattyToast("error", data.message ?? "Gagal upload foto.");
          }
        } catch (e) {
          flattyToast("error", "Gagal upload foto.");
        } finally {
          btn.innerHTML = "Upload";
          btn.disabled = false;
        }
      });
  }

  function escHtml(str) {
    if (!str) return "";
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function loadExplorePoi() {
    const wrap = document.getElementById("explorePoiList");
    const overlay = document.getElementById("exploreOverlay");
    let activeCatExplore = "";
    let searchQuery = "";
    let showAll = false;

    function render() {
      let filtered = POIS.filter((poi) => {
        const matchCat =
          !activeCatExplore || poi.category_id == activeCatExplore;
        const matchQ =
          !searchQuery || poi.name.toLowerCase().includes(searchQuery);
        return matchCat && matchQ;
      });

      if (!filtered.length) {
        wrap.innerHTML = `<div class="tp-empty-state">
                  <i class="fa-solid fa-magnifying-glass"></i>
                  <p>Tidak ada hasil.</p>
                </div>`;
        overlay.style.display = "none";
        return;
      }

      const limited =
        !showAll && filtered.length > 10 ? filtered.slice(0, 10) : filtered;

      wrap.innerHTML = limited
        .map(
          (poi) => `
        <div class="card card-flatty">
        <div class="card-body">
        ${
          poi.poi_image
            ? `<img src="${escHtml(poi.poi_image)}" class="card-img" onerror="this.src='/uploads/poi-placeholder.jpg'">`
            : `<img src="/uploads/poi-placeholder.jpg" class="card-img">`
        }
        <h3 class="h5 mb-2">${escHtml(poi.name)}</h3>
        <p class="text-muted mb-2">${escHtml(poi.description || "Deskripsi belum tersedia.")}</p>
        </div>
        <div class="card-footer">
        ${
          poi.slug
            ? `<a href="/poi/${escHtml(poi.slug)}" class="btn btn-primary" target="_blank" rel="noopener">
        Selengkapnya
        <i class="arrow-icon fas fa-angle-right ms-1"></i>
        </a>`
            : `<span class="text-red small">
        Belum ada link
        </span>`
        }
        </div>
        </div>
        `,
        )
        .join("");

      overlay.style.display =
        !showAll && filtered.length > 10 ? "flex" : "none";
    }

    document.getElementById("btnShowAllPoi").addEventListener("click", () => {
      showAll = true;
      render();
    });

    document
      .getElementById("exploreSearch")
      .addEventListener("input", function () {
        showAll = false;
        searchQuery = this.value.toLowerCase().trim();
        render();
      });

    document.querySelectorAll(".explore-cat").forEach((btn) => {
      btn.addEventListener("click", function () {
        document.querySelectorAll(".explore-cat").forEach((b) => {
          b.classList.remove("active");
        });
        this.classList.add("active");
        activeCatExplore = this.dataset.cat;
        showAll = false;
        render();
      });
    });

    document
      .getElementById("btnResetExploreSearch")
      .addEventListener("click", function () {
        searchQuery = "";
        activeCatExplore = "";
        showAll = false;
        document.getElementById("exploreSearch").value = "";

        document.querySelectorAll(".explore-cat").forEach((b) => {
          b.classList.remove("active");
        });
        document
          .querySelector('.explore-cat[data-cat=""]')
          .classList.add("active");
        render();
      });

    render();
  }

  async function loadTripku() {
    const wrap = document.getElementById("tripkuList");
    wrap.innerHTML = `<div class="skeleton-wrapper" style="grid-column:1/-1"><div></div></div>`;
    await new Promise((r) => setTimeout(r, 1000));
    fetch(API_TRIP)
      .then((r) => r.json())
      .then((data) => {
        const list = Array.isArray(data) ? data : data.data || [];
        const countEl = document.getElementById("profileTripCount");
        if (countEl) countEl.textContent = list.length;
        if (!list.length) {
          wrap.innerHTML = `<div class="tp-empty-state" style="grid-column:1/-1"><i class="fas fa-bookmark"></i><p>Belum ada trip tersimpan.</p></div>`;
          return;
        }
        wrap.innerHTML = list
          .map((trip) => {
            const isAi = trip.source === "itinerary";
            const badge = isAi
              ? `<span class="badge badge-accent ms-2" style="font-size:.7rem"><i class="fas fa-wand-magic-sparkles me-1"></i>Itinerary</span>`
              : "";
            const openBtn = isAi
              ? `<button class="btn btn-primary" onclick="window.renderAiItinerary(${trip.id})">Buka<i class="fa-solid fa-wand-magic-sparkles ms-1"></i></button>`
              : `<button class="btn btn-primary" onclick="loadSavedTrip(${trip.id})">Buka<i class="fa-solid fa-route ms-1"></i></button>`;
            return `
          <div class="card card-glass">
          <div class="card-body">
          <h3 class="h4 text-truncate">${escHtml(trip.title || "Trip tanpa nama")}${badge}</h3>
          <div class="row g-2">
          ${!isAi ? `<span class="small p-2"><i class="fa-solid fa-map-pin me-2"></i><strong>${trip.total_stops}</strong> lokasi</span>` : ""}
          ${!isAi && trip.total_distance ? `<span class="small p-2"><i class="fa-solid fa-ruler me-2"></i>Total jarak : <strong>${trip.total_distance}</strong> km</span>` : ""}
          </div>
          </div>
          <div class="card-footer d-flex gap-2">
          ${openBtn}
          <button class="btn btn-danger" onclick="window.deleteTripById(${trip.id}, '${escHtml(trip.title || "Trip ini")}')">
          Hapus<i class="fa-solid fa-trash ms-1"></i>
          </button>
          </div>
          </div>`;
          })
          .join("");
      })
      .catch(() => {
        wrap.innerHTML = `<div class="tp-empty-state" style="grid-column:1/-1"><i class="fa-solid fa-triangle-exclamation"></i><p>Gagal memuat trip. Coba refresh halaman.</p></div>`;
      });
  }

  window.refreshTripku = loadTripku;

  window.loadSavedTrip = function (tripId) {
    document.querySelector('[data-tab="map"]').click();
    if (typeof window.loadTripById === "function") window.loadTripById(tripId);
  };

  window.openPoiGallery = function (poiId) {
    if (typeof window.showPoiGallery === "function")
      window.showPoiGallery(poiId);
  };

  (function initTabs() {
    const tabs = document.querySelectorAll(".tp-tab");
    const contents = document.querySelectorAll(".tp-tab-content");
    let tripkuLoaded = false;
    let mapInitialized = false;

    tabs.forEach((tab) => {
      tab.addEventListener("click", function () {
        tabs.forEach((t) => t.classList.remove("active"));
        this.classList.add("active");
        const target = this.dataset.tab;
        contents.forEach((c) => (c.style.display = "none"));
        document.getElementById("tab-" + target).style.display = "";

        if (target === "tripku" && !tripkuLoaded && IS_LOGGED) {
          loadTripku();
          tripkuLoaded = true;
        }
        if (target === "map" && !mapInitialized) {
          setTimeout(() => {
            if (window.mainMap) window.mainMap.invalidateSize();
          }, 50);
          mapInitialized = true;
        }
      });
    });

    loadExplorePoi();

    if (IS_LOGGED) {
      loadTripku();
      tripkuLoaded = true;

      fetch(API_TRIP + "?action=count")
        .then((r) => r.json())
        .then((d) => {
          const el = document.getElementById("profileTripCount");
          if (el && d.count !== undefined) el.textContent = d.count;
        })
        .catch(() => {});
    }
  })();

  // auto trigger layanan publik filter explore  dari hash URL
  const hash = window.location.hash.replace("#", "");
  if (hash) {
    const [tab, filter] = hash.split(":");
    const tabEl = document.querySelector(`[data-tab="${tab}"]`);
    if (tabEl) tabEl.click();
    if (filter) {
      setTimeout(() => {
        const catBtn = document.querySelector(
          `.explore-cat[data-cat="${filter}"]`,
        );
        if (catBtn) catBtn.click();
      }, 100);
    }
  }

  const urlParams = new URLSearchParams(window.location.search);
  const openTrip = urlParams.get("open_trip");
  const openAi = urlParams.get("open_ai");

  if (openTrip) {
    setTimeout(() => {
      document.querySelector('[data-tab="map"]')?.click();
      if (typeof window.loadTripById === "function")
        window.loadTripById(parseInt(openTrip));
    }, 300);
  }

  if (openAi) {
    setTimeout(() => {
      if (typeof window.renderAiItinerary === "function")
        window.renderAiItinerary(parseInt(openAi));
    }, 300);
  }
})();
