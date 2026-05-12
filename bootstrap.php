<?php
if (!function_exists("autoload_core")) {
    function autoload_core() {
      $dir = __DIR__;
      $constants = $dir . "/config/constants.php";
      $db        = $dir . "/config/db.php";
      $key       = $dir . "/config/key.php";
      $helper       = $dir . "/lib/helper.php";

        if (file_exists($constants)) require_once $constants;
        if (file_exists($db))        require_once $db;
        if (file_exists($key))       require_once $key;
        if (file_exists($helper))       require_once $helper;
    }
}