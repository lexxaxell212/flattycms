<?php
if (defined("SETUP_LOADED")) return;
define("SETUP_LOADED", true);

// 1. DYNAMIC BASE URL (Biar bisa jalan di IP VPS maupun Domain)
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== 'off') ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$base_url = $protocol . "://" . $host . "/";

if (!defined("BASE_URL")) define("BASE_URL", $base_url);

// 2. CORE PATH (Otomatis deteksi lokasi folder)
define("ROOT_PATH", __DIR__ . "/");
define("SITE_NAME", "Ayokebandung.id");

// 3. ASSETS (Pakai BASE_URL biar dinamis)
define("ASSETS_URL", BASE_URL . "assets/");
define("CSS_URL",    ASSETS_URL . "css/");
define("JS_URL",     ASSETS_URL . "js/");
define("IMG_URL",    ASSETS_URL . "images/");
define("FONTS_URL",  ASSETS_URL . "fonts/");

// 4. PAGES URL
define("PAGES_URL", BASE_URL . "pages/");
define("ADMIN_URL", BASE_URL . "admin/");
define("BLOG_URL",  BASE_URL . "blogs/");
define("API_URL",   BASE_URL . "api/");

// 5. FILES & UPLOADS (Disesuaikan dengan mapping: admin/uploads)
define("UPLOADS_URL",  ADMIN_URL . "uploads/");
define("UPLOADS_PATH", ROOT_PATH . "admin/uploads/");
define("BASE_UPLOAD_URL", ROOT_PATH . "admin/uploads/");

// 6. SYSTEM PATHS (Disesuaikan dengan struktur folder kamu)
define("LOGS_PATH",     ROOT_PATH . "logs/");
define("INCLUDES_PATH", ROOT_PATH . "includes/");
define("LIB_PATH",      ROOT_PATH . "lib/");
define("PARTS_PATH",    ROOT_PATH . "parts/"); // Tambahan karena kamu punya folder parts

// 7. APP SETTINGS
define("APP_TIMEZONE", "Asia/Jakarta");
define("DEBUG_MODE", in_array($_SERVER["SERVER_NAME"], ["localhost", "127.0.0.1"]));
date_default_timezone_set(APP_TIMEZONE);
