<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('GET');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://ayokebandung.id');

// OPENWEATHER (backup, nonaktif)
/*
$lang = $_GET['lang'] ?? 'id';
$lang = in_array($lang, ['id', 'en']) ? $lang : 'id';
$url = "https://api.openweathermap.org/data/2.5/weather?lat=-6.906&lon=107.613&appid=" . WEATHER_API_KEY . "&units=metric&lang={$lang}";
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
*/

// BMKG
$url = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=32.73.04.1003";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
]);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
}
curl_close($ch);

if (!$result) {
  http_response_code(500);
  echo json_encode([
      'error' => 'Failed to connect BMKG server.',
      'debug' => $error_msg ?? 'Unknown cURL error'
  ]);
  exit;
}

$data = json_decode($result, true);

if (!$data || !isset($data['data']) || empty($data['data'])) {
  http_response_code(502);
  echo json_encode([
      'error' => 'Failed to load BMKG data.',
      'raw_status' => $data['status'] ?? 'Koneksi OK tapi struktur kosong'
  ]);
  exit;
}

echo json_encode($data['data'][0]);