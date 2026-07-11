<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 http_response_code(405);
 echo json_encode(['success' => false, 'message' => 'Method not allowed']);
 exit;
}

verify_ajax_request('POST');

// Koordinat dikirim sebagai JSON string
$coordinates = json_decode($_POST['coordinates'] ?? '[]', true);

if (empty($coordinates) || count($coordinates) < 2) {
 http_response_code(400);
 echo json_encode(['success' => false, 'message' => 'Minimal 2 koordinat diperlukan']);
 exit;
}

// Format ORS: [[lng, lat], [lng, lat], ...]
$ors_coords = array_map(fn($c) => [(float)$c[1], (float)$c[0]], $coordinates);

$payload = json_encode([
 'coordinates' => $ors_coords,
 'instructions' => false,
 'units' => 'km',
 'geometry' => true,
 'geometry_simplify' => false,
]);

$ctx = stream_context_create([
 'http' => [
  'method' => 'POST',
  'header' => implode("\r\n", [
   'Content-Type: application/json',
   'Authorization: ' . ORS_API_KEY,
   'Accept: application/json, application/geo+json',
  ]),
  'content' => $payload,
  'timeout' => 10,
 ]
]);

$raw = @file_get_contents(
 'https://api.openrouteservice.org/v2/directions/driving-car/geojson',
 false,
 $ctx
);

if ($raw === false) {
 http_response_code(502);
 echo json_encode(['success' => false, 'message' => 'Tidak bisa menghubungi ORS']);
 exit;
}

$data = json_decode($raw, true);

if (empty($data['features'][0])) {
 http_response_code(502);
 echo json_encode(['success' => false, 'message' => 'Response ORS tidak valid']);
 exit;
}

$feature = $data['features'][0];
$summary = $feature['properties']['summary'] ?? [];
$geometry = $feature['geometry']['coordinates'] ?? [];

// Convert balik ke [lat, lng] buat Leaflet
$polyline = array_map(fn($c) => [$c[1], $c[0]], $geometry);

echo json_encode([
 'success' => true,
 'polyline' => $polyline,
 'distance' => round(($summary['distance'] ?? 0), 2),
 'duration' => round(($summary['duration'] ?? 0) / 60, 1), // menit
]);