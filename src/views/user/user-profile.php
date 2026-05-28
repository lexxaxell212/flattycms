<?php
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
$page_title = 'Profil Saya — ' . SITE_NAME;
$user       = $_SESSION['user'];
$display    = $user['display_name'] ?? $user['name'];
?>

<script src="<?= JS_URL ?>user-profile.js" defer></script>

<main id="content">
<div class="container">

  <!-- Profile Header -->
  <section id="user-profile">
    <div class="card bg-primary mx-auto" style="max-width:740px">
    <div class="card-body">
      <div class="d-flex align-items-center gap-4 flex-wrap">
        <?php if (!empty($user['avatar'])): ?>
            <img src="<?= safe_html($user['avatar']) ?>"
                 class="rounded-circle shadow"
                 width="80" height="80"
                 style="object-fit:cover">
        <?php else: ?>
            <div class="rounded-circle bg-gray d-flex align-items-center justify-content-center fw-semibold"
                 style="width:80px;height:80px;font-size:28px;">
                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div class="flex-grow-1">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <h5 class="fw-bold mb-0 text-white" id="displayNameText"><?=
            safe_html($display) ?></h5>
            <button class="btn bg-gray p-1" id="btnEditName">
              <i class="fa-solid fa-pen fa-xs me-1"></i>Edit
            </button>
          </div>
          <div class="text-white small mt-1">
            <i class="fa-brands fa-google me-1"></i><?=
            safe_html($user['email']) ?>
          </div>
        </div>
      </div>

      <!-- Edit name form -->
      <div id="editNameForm" class="mt-3" style="display:none">
        <div class="input-group" style="max-width:440px">
          <input type="text" id="inputDisplayName" class="form-control text-white"
                 value="<?= safe_html($display) ?>" maxlength="100"
                 placeholder="Nama tampilan...">
          <button class="btn btn-accent" id="btnSaveName">
            <i class="fa-solid fa-check me-1"></i>Simpan
          </button>
          <button class="btn btn-outline-accent btn-sm" id="btnCancelName">Batal</button>
        </div>
      </div>
    </div>
  </div>
  </section>

  <!-- Stats -->
  <div class="row g-3 mb-4" id="statsRow">
    <div class="col-4">
      <div class="bg-light text-center py-3">
        <div class="fw-bold fs-5 text-primary" id="statPhotos">—</div>
        <div class="small">Foto</div>
      </div>
    </div>
    <div class="col-4">
      <div class="bg-light text-center py-3">
        <div class="fw-bold fs-5 text-success" id="statTrips">—</div>
        <div class="small">Trip</div>
      </div>
    </div>
    <div class="col-4">
      <div class="bg-light text-center py-3">
        <div class="fw-bold fs-5 text-danger" id="statReactions">—</div>
        <div class="small">Reaksi</div>
      </div>
    </div>
  </div>

  <!-- Tabs -->
  <div class="bg-light">
    <div class="pb-0 mx-auto" style="max-width:440px">
      <ul class="nav nav-tabs" id="profileTabs">
        <li class="nav-item">
          <button class="nav-link active" data-tab="photos">
            <i class="fa-solid fa-images me-1"></i>Foto
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="trips">
            Trip
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="reactions">
            <i class="fa-solid fa-heart me-1"></i>Reaksi
          </button>
        </li>
      </ul>
    </div>
    <div class="card-body p-4">

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
</main>