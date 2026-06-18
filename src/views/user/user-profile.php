<?php
if (!isset($_SESSION['user'])) {
  header('Location: /login');
  exit;
}
$page_title = 'Profil - ' . SITE_NAME;
$user = $_SESSION['user'];
$display = $user['display_name'] ?? $user['name'];
$initials = strtoupper(substr($user['name'] ?? 'U', 0, 1));
?>
<script src="<?= JS_URL ?>user-profile.js" defer></script>
<main class="main-content">
  <div class="container">
    <div class="up-layout my-5">
      <aside class="up-sidebar">
        <div class="bg-surface">
          <div class="text-center">
            <?php if (!empty($user['avatar'])): ?>
            <img src="<?= safe_html($user['avatar']) ?>"
            class="rounded-circle mb-3"
            width="80" height="80"
            style="object-fit:cover;border:2px solid var(--btn-bg-primary)">
            <?php else : ?>
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
              style="width:80px;height:80px;font-size:28px;background:var(--bg-primary-subtle);color:var(--text-primary);border:var(--border-primary-subtle)">
              <?= $initials ?>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap mb-1">
              <span class="h4 mb-0" id="displayNameText"><?= safe_html($display) ?>
              </span>
              <button class="btn btn-accent p-2" id="btnEditName" style="font-size:.85rem">
                <i class="fa-solid fa-pen fa-xs"></i>
              </button>
            </div>
            <div class="small mb-3">
              <div class="badge badge-primary">
                <i class="fa-brands fa-google me-2"></i>
                <?= safe_html($user['email']) ?>
              </div>
            </div>
            <div id="editNameForm" style="display:none" class="mb-2">
              <input type="text" id="inputDisplayName" class="form-control mb-2" value="<?= safe_html($display) ?>" maxlength="100" data-bhs="form.display.name" placeholder="Nama tampilan...">
              <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1" id="btnSaveName">
                  <i class="fa-solid fa-check me-2"></i><span data-bhs="btn.save">Simpan</span>
                </button>
                <button class="btn btn-outline-primary btn-sm" id="btnCancelName" data-bhs="btn.cancel">Batal</button>
              </div>
            </div>
            <div class="row g-2 text-center mt-4">
              <div class="col-4">
                <div class="fw-bold" id="statPhotos">
                  -
                </div>
                <div style="font-size:.7rem">
                  <span class="badge badge-primary" data-bhs="up.photo">Foto</span>
                </div>
              </div>
              <div class="col-4">
                <div class="fw-bold" id="statTrips">
                  -
                </div>
                <div style="font-size:.7rem">
                  <span class="badge badge-primary" data-bhs="up.trip">Trip</span>
                </div>
              </div>
              <div class="col-4">
                <div class="fw-bold" id="statReactions">
                  -
                </div>
                <div style="font-size:.7rem">
                  <span class="badge badge-primary" data-bhs="up.reaction">Reaksi</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="up-tab-nav" id="profileTabs">
          <button class="up-tab-btn active" data-tab="photos">
            <i class="fa-solid fa-images" style="width:16px"></i><span data-bhs="up.photo">Foto</span>
            <span class="up-tab-count" id="countPhotos">-</span>
          </button>
          <button class="up-tab-btn" data-tab="trips">
            <i class="fa-solid fa-route" style="width:16px"></i><span data-bhs="up.trip">Trip</span>
            <span class="up-tab-count" id="countTrips">-</span>
          </button>
          <button class="up-tab-btn" data-tab="reactions">
            <i class="fa-solid fa-heart" style="width:16px"></i><span data-bhs="up.reaction">Reaksi</span>
            <span class="up-tab-count" id="countReactions">-</span>
          </button>
        </div>
      </aside>
      <div class="up-content">
        <div class="bg-surface my-4">
          <div class="p-md-4">
            <div id="tabLoading" class="skeleton-wrapper">
              <div></div>
            </div>
            <div id="tabPhotos" class="tab-content" style="display:none">
              <div class="row g-3" id="photoGrid"></div>
            </div>
            <div id="tabTrips" class="tab-content" style="display:none">
              <div id="tripList" class="row g-3"></div>
            </div>
            <div id="tabReactions" class="tab-content" style="display:none">
              <div id="reactionList" class="row g-3"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>