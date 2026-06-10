<?php if (empty($_SESSION['user'])): ?>
<div class="d-flex align-items-center gap-2">
  <div id="<?= $google_btn_id ?>"></div>
  <div class="divider-v"></div>
  <a href="/login" class="btn btn-outline-primary btn-sm">
    <i class="fa-solid fa-user fa-sm"></i>
    Masuk
  </a>
  <a href="/register" class="btn btn-outline-primary btn-sm">
    <i class="fa-solid fa-pen fa-sm"></i>
    Daftar
  </a>
</div>
<?php else : ?>
<div class="d-flex align-items-center gap-2">
  <a href="/profile" class="profile-pill">
    <?php if (!empty($_SESSION['user']['avatar'])): ?>
    <img src="<?= safe_html($_SESSION['user']['avatar']) ?>" class="rounded-circle" width="30" height="30" style="object-fit:cover;flex-shrink:0;">
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
  <a href="/api/auth/logout.php" class="btn btn-outline-primary btn-sm">
    <i class="fa-solid fa-right-from-bracket me-1 fa-sm"></i>
    Logout
  </a>
</div>
<?php endif; ?>