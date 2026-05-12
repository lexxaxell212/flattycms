<?php
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        if (defined('APP_ENV') && APP_ENV === 'development') {
            echo "<pre style='background:rgba(29,78,216,0.08);color:rgba(29,78,216,0.5);padding:20px;border-left:4px solid rgba(29,78,216,0.5);'>";
            echo "Fatal Error: " . $error['message'] . "\n";
            echo "File: " . $error['file'] . "\n";
            echo "<span style='color:#fff;'>Line: " . $error['line'] . "</span>";
            echo "</pre>";
        } else {
            include __DIR__ . '/public/errors/500.php';
        }
        exit;
    }
});

if (!function_exists("autoload_core")) {
  function autoload_core() {
    $dir    = __DIR__;
    $app    = $dir . "/config/app.php";
    $db     = $dir . "/config/db.php";
    $key    = $dir . "/config/key.php";
    $helper = $dir . "/lib/helper.php";

    if (file_exists($app)) require_once $app;
    if (file_exists($db)) require_once $db;
    if (file_exists($key)) require_once $key;
    if (file_exists($helper)) require_once $helper;
  }
}