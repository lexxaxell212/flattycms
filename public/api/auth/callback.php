<?php
require_once dirname(__DIR__, 3) . "/bootstrap.php";
autoload_core();

$config = require_once dirname(__DIR__, 3) . "/config/oauth.php";

// Tukar code dengan access token
$code = $_GET['code'] ?? '';
if (!$code) { header('Location: /'); exit; }

$response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query([
            'code'          => $code,
            'client_id'     => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri'  => $config['redirect_uri'],
            'grant_type'    => 'authorization_code',
        ]),
    ]
]));

$token = json_decode($response, true);
$accessToken = $token['access_token'] ?? '';
if (!$accessToken) { header('Location: /'); exit; }

// Fetch profile user
$profile = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v3/userinfo', false, stream_context_create([
    'http' => ['header' => 'Authorization: Bearer ' . $accessToken]
])), true);

// Simpen ke DB & bikin session
$user = dbQuerySingle("SELECT * FROM users WHERE google_id = ?", [$profile['sub']]);
if (!$user) {
    dbExecute("INSERT INTO users (google_id, name, email, avatar) VALUES (?, ?, ?, ?)", [
        $profile['sub'], $profile['name'], $profile['email'], $profile['picture']
    ]);
    $user = dbQuerySingle("SELECT * FROM users WHERE google_id = ?", [$profile['sub']]);
}

$_SESSION['user'] = $user;
header('Location: /');
exit;