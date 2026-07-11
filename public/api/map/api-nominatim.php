<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
 http_response_code(401);
 echo json_encode(['success' => false, 'message' => 'Unauthorized']);
 exit;
}

$q = trim($_GET['q'] ?? '');
if (!$q) {
 http_response_code(400);
 echo json_encode(['success' => false, 'message' => 'Query kosong']);
 exit;
}

$url = 'https://nominatim.openstreetmap.org/search?'
. http_build_query([
 'q' => $q,
 'format' => 'json',
 'limit' => 5,
 'countrycodes' => 'id',
 'accept-language' => 'id',
]);

$ctx = stream_context_create([
 'http' => [
  'method' => 'GET',
  'header' => "User-Agent: ayokebandung.id/1.0 (admin@ayokebandung.id)\r\n",
  'timeout' => 5,
 ]
]);

$raw = @file_get_contents($url, false, $ctx);

if ($raw === false) {
 http_response_code(502);
 echo json_encode(['success' => false, 'message' => 'Tidak bisa menghubungi Nominatim']);
 exit;
}

$data = json_decode($raw, true);
if (!is_array($data)) {
 http_response_code(502);
 echo json_encode(['success' => false, 'message' => 'Response Nominatim tidak valid']);
 exit;
}

// Sederhanakan response
$results = array_map(fn($item) => [
 'display_name' => $item['display_name'],
 'lat' => $item['lat'],
 'lon' => $item['lon'],
], $data);

echo json_encode(['success' => true, 'data' => $results]);