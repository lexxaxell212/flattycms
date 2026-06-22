<?php
$maintenance = $pdo->query("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'")->fetchColumn();
$saved = isset($_GET['saved']);

$settings = $pdo->query("SELECT setting_key, setting_value FROM admin_setting")
->fetchAll(PDO::FETCH_KEY_PAIR);

$csrf = generate_csrf_token();
?>
<main class="main-content">
  <div class="container">
    <div class="page-header text-center">
      <h1>Site Setting</h1>
    </div>
    <div class="mx-auto" style="max-width:740px">
      <?php if ($saved): ?>
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
        <div id="settingToast" class="toast align-items-center text-bg-success border-0" role="alert">
          <div class="d-flex">
            <div class="toast-body">
              <i class="fa-solid fa-circle-check me-2"></i> Setting berhasil disimpan.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      </div>
      <script>
        const toastEl = document.getElementById('settingToast');
        bootstrap.Toast.getOrCreateInstance(toastEl, {
          delay: 3000
        }).show();
        toastEl.addEventListener('hidden.bs.toast', () => {
          const url = new URL(window.location);
          url.searchParams.delete('saved');
          history.replaceState({}, '', url);
        });
      </script>
      <?php endif; ?>
      <div class="card card-glass mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
              <i class="fa-solid fa-globe fa-sm"></i>
            </span>
            <span class="fw-semibold">Development</span>
          </div>
        </div>
        <div class="px-4 py-3">
          <div class="d-flex align-items-center justify-content-between py-2">
            <div>
              <div class="fw-medium mb-1">
                <i class="fa-solid fa-triangle-exclamation me-1 <?= $maintenance === '1' ? 'text-warning' : 'text-muted' ?>"></i>
                Maintenance Mode
              </div>
              <small class="text-muted">Saat aktif, pengunjung akan melihat halaman maintenance.</small>
            </div>
            <form method="POST" action="/admin/setting" class="ms-4 flex-shrink-0">
              <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
              <input type="hidden" name="action" value="toggle_maintenance">
              <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" role="switch"
                id="maintenanceToggle"
                style="width:2.8em;height:1.5em;cursor:pointer;"
                <?= $maintenance === '1' ? 'checked' : '' ?>
                onchange="this.form.submit()">
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card card-glass mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 text-primary rounded p-1 lh-1">
              <i class="fa-solid fa-circle-info fa-sm"></i>
            </span>
            <span class="fw-semibold">Site Information</span>
          </div>
        </div>
        <div class="px-4 py-3 text-start">
          <form method="POST" action="/admin/setting">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="save_settings">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-medium">Site Name</label>
                <input type="text" name="site_name" class="form-control" value="<?= safe_html($settings['site_name'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Tagline</label>
                <input type="text" name="site_tagline" class="form-control" value="<?= safe_html($settings['site_tagline'] ?? '') ?>">
              </div>
              <div class="col-12">
                <label class="form-label fw-medium">Deskripsi</label>
                <textarea name="site_description" class="form-control" rows="2"><?= safe_html($settings['site_description'] ?? '') ?></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control" value="<?= safe_html($settings['meta_keywords'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">OG Image URL</label>
                <input type="text" name="og_image" class="form-control" value="<?= safe_html($settings['og_image'] ?? '') ?>">
              </div>
            </div>

            <hr class="my-4">
            <p class="fw-semibold mb-3">
              Kontak
            </p>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?= safe_html($settings['contact_email'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">WhatsApp</label>
                <input type="text" name="contact_wa" class="form-control" value="<?= safe_html($settings['contact_wa'] ?? '') ?>">
              </div>
            </div>

            <hr class="my-4">
            <p class="fw-semibold mb-3">
              Social Media
            </p>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label fw-medium">Instagram</label>
                <input type="text" name="social_instagram" class="form-control" value="<?= safe_html($settings['social_instagram'] ?? '') ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-medium">TikTok</label>
                <input type="text" name="social_tiktok" class="form-control" value="<?= safe_html($settings['social_tiktok'] ?? '') ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-medium">Facebook</label>
                <input type="text" name="social_facebook" class="form-control" value="<?= safe_html($settings['social_facebook'] ?? '') ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-medium">Twitter Handle</label>
                <input type="text" name="twitter_handle" class="form-control" placeholder="@username" value="<?= safe_html($settings['twitter_handle'] ?? '') ?>">
              </div>
            </div>

            <hr class="my-4">
            <p class="fw-semibold mb-3">
              Analytics & Tracking
            </p>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-medium">Google Tag ID</label>
                <input type="text" name="gtag_id" class="form-control" placeholder="G-XXXXXXXXXX" value="<?= safe_html($settings['gtag_id'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium">Facebook Pixel ID</label>
                <input type="text" name="fb_pixel_id" class="form-control" value="<?= safe_html($settings['fb_pixel_id'] ?? '') ?>">
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Setting
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>