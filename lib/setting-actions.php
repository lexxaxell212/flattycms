<?php
// Maintenance toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_maintenance') {
  validate_csrf();
  $current = $pdo->query("SELECT setting_value FROM admin_setting WHERE setting_key = 'maintenance_mode'")->fetchColumn();
  $newVal = $current === '1' ? '0' : '1';
  $pdo->prepare("
        INSERT INTO admin_setting (setting_key, setting_value) VALUES ('maintenance_mode', :val)
        ON DUPLICATE KEY UPDATE setting_value = :val
    ")->execute([':val' => $newVal]);
  regenerate_csrf_token();
  header('Location: /admin/setting?saved=1');
  exit;
}

// Save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_settings') {
  validate_csrf();

  $allowed_keys = [
    'site_name',
    'site_tagline',
    'site_description',
    'meta_keywords',
    'og_image',
    'contact_email',
    'contact_wa',
    'social_instagram',
    'social_tiktok',
    'social_facebook',
    'twitter_handle',
    'gtag_id',
    'fb_pixel_id'
  ];

  $stmt = $pdo->prepare("
        INSERT INTO admin_setting (setting_key, setting_value)
        VALUES (:key, :val)
        ON DUPLICATE KEY UPDATE setting_value = :val
    ");

  foreach ($allowed_keys as $key) {
    if (isset($_POST[$key])) {
      $stmt->execute([':key' => $key, ':val' => trim($_POST[$key])]);
    }
  }

  regenerate_csrf_token();
  header('Location: /admin/setting?saved=1');
  exit;
}