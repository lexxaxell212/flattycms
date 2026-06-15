<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = trim($input['prompt'] ?? '');
$pois = $input['pois']   ?? [];

if (!$prompt || empty($pois)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Prompt dan data POI wajib diisi']);
  exit;
}

$poi_list = implode("\n", array_map(function($p) {
  return "- {$p['name']} | {$p['category_name']} | {$p['address']} | slug: {$p['slug']}";
}, $pois));

$system_prompt = <<<PROMPT
Kamu adalah asisten wisata Bandung. Berdasarkan permintaan user dan daftar POI yang tersedia, buatkan itinerary perjalanan.

Jika user tidak menyebutkan jumlah hari, defaultkan menjadi 2 hari.

Daftar POI tersedia:
{$poi_list}

Aturan:
- Hanya gunakan POI dari daftar di atas
- Susun per hari, per waktu (Pagi, Siang, Sore)
- Setiap slot maksimal 1 POI
- Berikan tips singkat per POI
- Response HANYA JSON, tanpa teks lain, tanpa markdown

Format response:
{
  "days": [
    {
      "day": 1,
      "slots": [
        {
          "waktu": "Pagi",
          "nama": "Nama POI",
          "slug": "slug-poi",
          "kategori": "Kategori",
          "tips": "Tips singkat kunjungan"
        }
      ]
    }
  ]
}
PROMPT;

$body = json_encode([
  'model' => 'llama-3.3-70b-versatile',
  'messages' => [
    ['role' => 'system', 'content' => $system_prompt],
    ['role' => 'user', 'content' => $prompt]
  ],
  'max_tokens' => 1500,
  'temperature' => 0.7,
]);

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => $body,
  CURLOPT_HTTPHEADER => [
    'Authorization: Bearer ' . GROQ_API_TRIP,
    'Content-Type: application/json'
  ],
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'cURL error: ' . $err]);
  exit;
}

$data = json_decode($response, true);

if (isset($data['error'])) {
  $errType = $data['error']['code'] ?? '';
  if ($errType === 'rate_limit_exceeded') {
    http_response_code(429);
    echo json_encode([
      'success' => false,
      'message' => 'Batas request AI tercapai, coba lagi dalam 1 menit.'
    ]);
    exit;
  }
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Gagal menghubungi AI']);
  exit;
}

$text = $data['choices'][0]['message']['content'] ?? '';

// strip markdown jika ada
$text = preg_replace('/```json|```/', '', $text);
$text = trim($text);

$itinerary = json_decode($text, true);

if (!$itinerary || !isset($itinerary['days'])) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Gagal parse response AI']);
  exit;
}

echo json_encode(['success' => true, 'data' => $itinerary]);