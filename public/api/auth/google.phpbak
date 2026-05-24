<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

$config = require_once dirname(__DIR__, 3) . "/config/oauth.php";

$params = http_build_query([
    'client_id'     => $config['client_id'],
    'redirect_uri'  => $config['redirect_uri'],
    'response_type' => 'code',
    'scope'         => 'openid email profile',
    'access_type'   => 'online',
]);

$_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
exit;