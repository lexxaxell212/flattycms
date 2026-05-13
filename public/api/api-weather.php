<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('GET');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://ayokebandung.id');

$city = $_GET['city'] ?? 'Bandung';
$city = htmlspecialchars(strip_tags($city));

$url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid=" . WEATHER_API_KEY . "&units=metric&lang=id";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
curl_close($ch);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menghubungi server cuaca']);
    exit;
}

$data = json_decode($result, true);

if (!$data || (int)$data['cod'] !== 200) {
    http_response_code(502);
    echo json_encode(['error' => 'Gagal ambil data cuaca', 'detail' => $data['message'] ?? '']);
    exit;
}

echo json_encode($data);
?>