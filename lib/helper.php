<?php
// site setting
function load_site_settings(): array {
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) return [];
    
    try {
        $settings = $pdo->query("SELECT setting_key, setting_value FROM admin_setting")
                        ->fetchAll(PDO::FETCH_KEY_PAIR);
        $GLOBALS['site_settings'] = $settings;
        return $settings;
    } catch (Exception $e) {
        return [];
    }
}

// Cookies
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        "cookie_httponly" => true,
        "cookie_secure"   => isset($_SERVER["HTTPS"]),
        "cookie_samesite" => "Lax",
        "gc_maxlifetime" => defined("SESSION_EXPIRE") ? SESSION_EXPIRE : 86400,
    ]);
}

// CSRF token
function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function regenerate_csrf_token(): void {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verify_csrf_token(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function validate_csrf(): void {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals((string)$_SESSION['csrf_token'], (string)$_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Invalid CSRF token. Silakan refresh halaman dan coba lagi.');
    }
}

// Safe HTML
function safe_html($value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Safe include
function safe_include($file_path, $fallback_title = "Konten") {
    if (!file_exists($file_path)) {
        echo fallback_card($fallback_title);
        return;
    }
    // inject global variables ke function scope
    extract($GLOBALS);
    ob_start();
    try {
        include $file_path;
        ob_end_flush();
    } catch (Throwable $e) {
        ob_end_clean();
        echo fallback_card($fallback_title);
    }
}

function fallback_card($title = "Konten")
{
  return '
    <div class="container py-5">
      <div class="row mx-auto">
        <div class="col-12">
            <div class="card card-glass">
                <div class="card-body text-center">
                    <i class="fas fa-circle-notch fa-spin fa-1x text-muted mb-3"></i>
                    <h2 class="text-muted mb-1">' .
    htmlspecialchars($title) .
    '</h2>
                    <p class="text-muted small mb-0">Segera hadir.</p>
                </div>
            </div>
        </div>
      </div>
    </div>';
}

// image upload
function fix_image_paths(string $html, string $post_title = ""): string
{
  $base = rtrim(BASE_UPLOAD_URL, "/") . "/";
  $safe_alt = htmlspecialchars(
    $post_title ?: "Gambar artikel",
    ENT_QUOTES,
    "UTF-8",
  );
  return preg_replace_callback(
    '/<img([^>]*?)\ssrc="uploads\/([^"]*)"([^>]*?)(\s*\/?>)/i',
    function (array $m) use ($base, $safe_alt): string {
      [$full, $before, $filename, $after, $close] = $m;
      if (!preg_match('/^[\w\-\.]+\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
        return "";
      }
      $safe_src = htmlspecialchars($base . $filename, ENT_QUOTES, "UTF-8");
      $has_alt = (bool) preg_match("/\balt\s*=/i", $before . $after);
      $alt_attr = $has_alt ? "" : ' alt="' . $safe_alt . '"';
      return "<img" .
        $before .
        ' src="' .
        $safe_src .
        '"' .
        $after .
        $alt_attr .
        $close;
    },
    $html,
  ) ?? $html;
}

// sanitize 
function sanitize_content(string $html, string $post_title = ""): string
{
  $allowed =
    "<p><br><b><i><u><s><strong><em><ul><ol><li><blockquote><h1><h2><h3><h4><h5><h6><a><img><span><div><figure><figcaption><table><thead><tbody><tr><th><td>";
  return fix_image_paths(strip_tags($html, $allowed), $post_title);
}

// safe excerpt
function safe_excerpt(string $raw, int $max = 160): string
{
  $plain = strip_tags($raw);
  if (mb_strlen($plain) > $max) {
    $cut = mb_substr($plain, 0, $max);
    $last_space = mb_strrpos($cut, " ");
    $plain =
      ($last_space !== false ? mb_substr($cut, 0, $last_space) : $cut) . "…";
  }
  return htmlspecialchars($plain, ENT_QUOTES, "UTF-8");
}

// fmt date
function fmt_date(string $val, string $fmt = "d M Y"): string
{
  $ts = strtotime($val);
  return $ts !== false ? date($fmt, $ts) : "-";
}