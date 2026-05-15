<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();
session_destroy();
header('Location: /');
exit;