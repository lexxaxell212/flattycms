<?php
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
$page_title = 'Profil Saya — ' . SITE_NAME;
$user       = $_SESSION['user'];
$display    = $user['display_name'] ?? $user['name'];
$initials   = strtoupper(substr($user['name'] ?? 'U', 0, 1));
?>

<script src="<?= JS_URL ?>user-profile.js" defer></script>

<style>
/* ── PROFILE LAYOUT ── */
.up-layout {
  display: flex;
  gap: 1.5rem;
  align-items: flex-start;
}
.up-sidebar {
  width: 280px;
  flex-shrink: 0;
  position: sticky;
  top: 80px;
}
.up-content {
  flex: 1;
  min-width: 0;
}

/* Mobile: stack */
@media (max-width: 768px) {
  .up-layout { flex-direction: column; }
  .up-sidebar { width: 100%; position: static; }
}

/* ── SIDEBAR TABS ── */
.up-tab-nav {
  display: flex;
  flex-direction: column;
  gap: .25rem;
  margin-top: 1rem;
}
.up-tab-btn {
  display: flex;
  align-items: center;
  gap: .6rem;
  padding: .6rem .9rem;
  border: none;
  border-radius: .6rem;
  background: transparent;
  color: var(--text-secondary, #6c757d);
  font-size: .9rem;
  font-weight: 500;
  cursor: pointer;
  transition: background .15s, color .15s;
  text-align: left;
  width: 100%;
}
.up-tab-btn:hover {
  background: var(--bg-subtle, oklch(0.97 0.01 295));
  color: var(--text-primary);
}
.up-tab-btn.active {
  background: oklch(0.95 0.04 295);
  color: var(--purple-main, oklch(0.487 0.167 295));
  font-weight: 600;
}
.up-tab-btn .up-tab-count {
  margin-left: auto;
  font-size: .72rem;
  background: oklch(0.487 0.167 295);
  color: #fff;
  border-radius: 99px;
  padding: .1rem .45rem;
  font-weight: 600;
  min-width: 20px;
  text-align: center;
}

/* Mobile tabs: horizontal */
@media (max-width: 768px) {
  .up-tab-nav {
    flex-direction: row;
    overflow-x: auto;
    gap: .4rem;
    margin-top: .75rem;
    padding-bottom: .25rem;
  }
  .up-tab-btn {
    flex-shrink: 0;
    padding: .45rem .8rem;
    font-size: .82rem;
    border-radius: 99px;
    border: 1.5px solid oklch(0.9 0.04 295);
  }
  .up-tab-btn.active {
    border-color: oklch(0.487 0.167 295);
  }
  .up-tab-btn .up-tab-count { display: none; }
}
</style>

<main id="content">
<div class="container py-4">
<div class="up-layout">

  <!-- ── SIDEBAR ── -->
  <aside class="up-sidebar">

    <!-- Profile Card -->
    <div class="card card-flatty">
      <div class="card-body text-center">

        <!-- Avatar -->
        <?php if (!empty($user['avatar'])): ?>
          <img src="<?= safe_html($user['avatar']) ?>"
               class="rounded-circle shadow-sm mb-3"
               width="80" height="80"
               style="object-fit:cover;border:3px solid oklch(0.487 0.167 295)">
        <?php else: ?>
          <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
               style="width:80px;height:80px;font-size:28px;background:oklch(0.487 0.167 295);color:#fff">
            <?= $initials ?>
          </div>
        <?php endif; ?>

        <!-- Name -->
        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap mb-1">
          <h6 class="fw-bold mb-0" id="displayNameText"><?= safe_html($display) ?></h6>
          <button class="btn btn-outline-primary btn-sm py-0 px-2" id="btnEditName" style="font-size:.72rem">
            <i class="fa-solid fa-pen fa-xs"></i>
          </button>
        </div>
        <div class="text-muted small mb-3">
          <i class="fa-brands fa-google me-1" style="color:oklch(0.487 0.167 295)"></i><?= safe_html($user['email']) ?>
        </div>

        <!-- Edit name form -->
        <div id="editNameForm" style="display:none" class="mb-2">
          <input type="text" id="inputDisplayName" class="form-control form-control-sm mb-2"
                 value="<?= safe_html($display) ?>" maxlength="100"
                 placeholder="Nama tampilan...">
          <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm flex-grow-1" id="btnSaveName">
              <i class="fa-solid fa-check me-1"></i>Simpan
            </button>
            <button class="btn btn-outline-primary btn-sm" id="btnCancelName">Batal</button>
          </div>
        </div>

        <!-- Stats -->
        <div class="row g-2 text-center mt-1">
          <div class="col-4">
            <div class="fw-bold text-purple" id="statPhotos">—</div>
            <div class="text-muted" style="font-size:.7rem">Foto</div>
          </div>
          <div class="col-4">
            <div class="fw-bold text-purple" id="statTrips">—</div>
            <div class="text-muted" style="font-size:.7rem">Trip</div>
          </div>
          <div class="col-4">
            <div class="fw-bold text-purple" id="statReactions">—</div>
            <div class="text-muted" style="font-size:.7rem">Reaksi</div>
          </div>
        </div>

      </div>
    </div>

    <!-- Tab Nav -->
    <div class="up-tab-nav" id="profileTabs">
      <button class="up-tab-btn active" data-tab="photos">
        <i class="fa-solid fa-images" style="width:16px"></i>Foto
        <span class="up-tab-count" id="countPhotos">—</span>
      </button>
      <button class="up-tab-btn" data-tab="trips">
        <i class="fa-solid fa-route" style="width:16px"></i>Trip
        <span class="up-tab-count" id="countTrips">—</span>
      </button>
      <button class="up-tab-btn" data-tab="reactions">
        <i class="fa-solid fa-heart" style="width:16px"></i>Reaksi
        <span class="up-tab-count" id="countReactions">—</span>
      </button>
    </div>

  </aside>

  <!-- ── CONTENT ── -->
  <div class="up-content">
    <div class="card card-flatty">
      <div class="card-body p-3 p-md-4">

        <!-- Loading -->
        <div id="tabLoading" class="text-center py-5 text-muted">
          <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block"></i>Memuat data...
        </div>

        <!-- Tab: Foto -->
        <div id="tabPhotos" class="tab-content" style="display:none">
          <div class="row g-3" id="photoGrid"></div>
        </div>

        <!-- Tab: Trip -->
        <div id="tabTrips" class="tab-content" style="display:none">
          <div id="tripList"></div>
        </div>

        <!-- Tab: Reaksi -->
        <div id="tabReactions" class="tab-content" style="display:none">
          <div id="reactionList"></div>
        </div>

      </div>
    </div>
  </div>

</div>
</div>
</main>