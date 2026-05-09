<?php
if (defined("SETUP_LOADED")) return;
define("SETUP_LOADED", true);
// https / http
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== 'off') ? "https" : "http";
$host = $_SERVER["HTTP_HOST"];
$base_url = $protocol . "://" . $host . "/";

// Base
if (!defined("BASE_URL")) define("BASE_URL", $base_url);
define("ROOT_PATH", __DIR__ . "/");

// Site name
define("SITE_NAME", "AYOKEBANDUNG.ID");

// Assets
define("ASSETS_URL", BASE_URL . "assets/");
define("CSS_URL",    ASSETS_URL . "css/");
define("JS_URL",     ASSETS_URL . "js/");
define("IMG_URL",    ASSETS_URL . "images/");
define("FONTS_URL",  ASSETS_URL . "fonts/");

// Pages
define("PAGES_URL", BASE_URL . "pages/");
define("BLOGS_URL",  BASE_URL . "blogs/");

// Admin dashboard
define("ADMIN_URL", BASE_URL . "admin/");
define("ADMIN_INCLUDES", BASE_URL . "admin/includes/");

// Uploads source
define("UPLOADS_URL",  BASE_URL . "uploads/");
// User uploads
define("BASE_UPLOAD_URL", ADMIN_URL );
// Admin uploads

// Core
define("LOGS_PATH",     ROOT_PATH . "logs/");
define("INCLUDES_PATH", ROOT_PATH . "includes/");
define("LIB_PATH",      ROOT_PATH . "lib/");
define("API_PATH",      ROOT_PATH . "api/");
define("CONFIG_PATH",   ROOT_PATH . "config/");
define("PARTS_PATH",    ROOT_PATH . "parts/");

// Etc
define("APP_TIMEZONE", "Asia/Jakarta");
define("DEBUG_MODE", in_array($_SERVER["SERVER_NAME"], ["localhost", "127.0.0.1"]));
date_default_timezone_set(APP_TIMEZONE);