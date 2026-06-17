<?php if (empty($_SESSION['user'])): ?>
<div class="d-flex align-items-center mx-auto">
  <div class="auth-btn-group">
  <div id="<?= $google_btn_id ?>" class="avatar-circle"></div>
  <div class="divider-v"></div>
  <a href="/login" class="btn btn-outline-primary btn-sm rounded-sm" data-bhs="btn.login">
    Masuk
  </a>
  <a href="/register" class="btn btn-primary btn-sm rounded-sm" data-bhs="btn.reg">
    Daftar
  </a>
  </div>
</div>
<?php else : ?>
<div class="d-flex align-items-center mx-auto">
  <div class="auth-btn-group">
  <a href="/profile" class="profile-pill">
    <?php if (!empty($_SESSION['user']['avatar'])): ?>
    <img src="<?= safe_html($_SESSION['user']['avatar']) ?>" class="rounded-circle" width="30" height="30" style="object-fit:cover;flex-shrink:0;border:1.5px solid var(--btn-bg-primary)">
    <?php else : ?>
    <div class="avatar-circle">
      <?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?>
    </div>
    <?php endif; ?>
    <span class="account-name text-truncate" style="max-width:120px;">
        <?= safe_html($_SESSION['user']['display_name'] ?? $_SESSION['user']['name']) ?>
    </span>
  </a>
  <div class="divider-v"></div>
  <a href="/api/auth/logout.php" class="btn btn-outline-primary btn-sm rounded-sm" data-bhs="btn.logout">
    Keluar
  </a>
  </div>
</div>
<?php endif; ?>