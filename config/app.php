<?php
if (defined("APP_LOADED")) return;
define("APP_LOADED", true);

// https / http
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== 'off') ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$base_url = $protocol . "://" . $host . "/";

// Base url
if (!defined("BASE_URL")) define("BASE_URL", $base_url);

// Base path
if (!defined("ROOT_PATH")) define("ROOT_PATH", dirname(__DIR__) . "/");

// Site name
define("SITE_NAME", "AYOKEBANDUNG.ID");
define("APP_NAME", "FLATTY-CMS");

// Assets
define("ASSETS_URL", BASE_URL . "assets/");
define("CSS_URL",    ASSETS_URL . "css/");
define("JS_URL",     ASSETS_URL . "js/");
define("IMG_URL",    ASSETS_URL . "images/");
define("FONTS_URL",  ASSETS_URL . "fonts/");
define("BASE_UPLOAD_URL", BASE_URL . "uploads/");

// Pages
define("PAGES_URL", BASE_URL . "pages/");
define("BLOGS_URL",  BASE_URL . "blogs/");

// Admin dashboard
define("ADMIN_URL", BASE_URL . "admin/");

// Core
define("LOGS_PATH",   ROOT_PATH . "errors/");
define("LIB_PATH",    ROOT_PATH . "lib/");
define("API_PATH",    ROOT_PATH . "api/");
define("CONFIG_PATH", ROOT_PATH . "config/");
define("SRC_PATH",    ROOT_PATH . "src/views/");
define("CACHE_PATH",  ROOT_PATH . "cache/");
define("BASE_UPLOAD_PATH", ROOT_PATH . "public/uploads/");

// Etc
define("APP_TIMEZONE", "Asia/Jakarta");
define("APP_VERSION",  "1.0.0");
define("DEBUG_MODE", in_array($_SERVER["SERVER_NAME"], ["localhost", "127.0.0.1"]));
define("APP_ENV",    DEBUG_MODE ? "development" : "production");
date_default_timezone_set(APP_TIMEZONE);

// Error handling
if (APP_ENV === "development") {
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", 0);
    ini_set("display_startup_errors", 0);
    error_reporting(0);
    ini_set("log_errors", 1);
    ini_set("error_log", LOGS_PATH . "php_error.log");
}

// Security
define("TOKEN_EXPIRE",   3600);
define("SESSION_EXPIRE", 86400);
define("COOKIE_EXPIRE",  2592000);
define("BCRYPT_COST",    12);
define("CSRF_EXPIRE",    7200);

// Upload
define("MAX_UPLOAD_SIZE",     5 * 1024 * 1024);
define("MAX_UPLOAD_SIZE_IMG", 2 * 1024 * 1024);
define("ALLOWED_IMG_EXT",     ["jpg", "jpeg", "png", "webp", "svg"]);
define("ALLOWED_FILE_EXT",    ["jpg", "jpeg", "png", "webp", "pdf", "doc", "docx"]);

// Pagination
define("PER_PAGE",       10);
define("PER_PAGE_ADMIN", 25);

// Cache
define("CACHE_EXPIRE",      3600);
define("CACHE_EXPIRE_LONG", 86400);