<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('GET');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://ayokebandung.id');

$city = $_GET['city'] ?? 'Bandung,ID';
$city = htmlspecialchars(strip_tags($city));
$lang = $_GET['lang'] ?? 'id';
$lang = in_array($lang, ['id', 'en']) ? $lang : 'id';
$url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid=" . WEATHER_API_KEY . "&units=metric&lang={$lang}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
curl_close($ch);

if (!$result) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to connect weather server.']);
  exit;
}

$data = json_decode($result, true);

if (!$data || (int)$data['cod'] !== 200) {
  http_response_code(502);
  echo json_encode(['error' => 'Failed to load weather data.', 'detail' => $data['message'] ?? '']);
  exit;
}

echo json_encode($data);
?>