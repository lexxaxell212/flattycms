<?php
if (defined('DB_LOADED')) return;
define('DB_LOADED', true);

try {
 $GLOBALS['pdo'] = new PDO(
  "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
  DB_USER, DB_PASS,
  [
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]
 );
} catch (PDOException $e) {
 error_log("DB Error: " . $e->getMessage(), 3, LOGS_PATH . 'db.log');
 $GLOBALS['pdo'] = null;
}