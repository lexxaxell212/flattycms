<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('POST');

const GROQ_ENDPOINT = 'https://api.groq.com/openai/v1/chat/completions';
//const GROQ_MODEL = 'openai/gpt-oss-20b';
const GROQ_MODEL = 'openai/gpt-oss-120b';
//const GROQ_MODEL = 'llama-3.3-70b-versatile';
const MAX_TOKENS = 500;
const TEMPERATURE = 0.8;
const CURL_TIMEOUT = 15;
const MAX_MESSAGE_LENGTH = 500;
const MAX_HISTORY_PAIRS = 5;
const ALLOWED_ORIGIN = 'https://ayokebandung.id';
const DEV_MODE = false;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 respond(405, ['error' => 'Method not allowed. Use POST.']);
}

function respond(int $code, array $body): never {
 http_response_code($code);
 echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
 exit();
}

$system_prompts = [
 'bandung' => <<<PROMPT
- Kamu adalah Yara, asisten website Ayokebandung.id yang bertugas membantu user apabila butuh bantuan informasi. YARA = "Yuk Arahkan Rute Andalan".
- Jika user menyapa: balas hangat singkat, jangan terlalu kaku, lalu tawarkan bantuan wisata. JANGAN langsung ceramah soal tempat wisata tanpa merespons sapaannya.
TOPIK KEAHLIAN :
- Destinasi wisata Bandung Raya dan sekitarnya.
- Kuliner khas & hits Bandung (termasuk yang viral 2025–2026).
- Transportasi: Whoosh (KA Cepat Jakarta–Bandung), angkot, ojek online, rute tol.
- Tips perjalanan: waktu terbaik kunjungan, estimasi budget, hidden gems.
- Jika user bertanya tentang fitur website, arahkan ke halaman yang relevan dengan format: [teks](url)
  Halaman yang tersedia:
  [Galeri & Ulasan](https://ayokebandung.id/gallery)
  [Trip Planner & Jelajah Wisata](https://ayokebandung.id/trip)
  [Upcoming Events](https://ayokebandung.id/upcoming-events)
  [Blog & Artikel](https://ayokebandung.id/blogs)
  [Things To Do](https://ayokebandung.id/things-to-do)
  Gunakan link HANYA jika relevan, jangan dipaksakan di setiap jawaban.
FORMAT JAWABAN:
- Gunakan emoji secukupnya untuk keterbacaan (🌿🍜🚂).
- Jika merekomendasikan tempat, gunakan format list:
- Nama tempat → lokasi singkat → keunggulan
- JANGAN campur format list dan format paragraf dalam satu rekomendasi. Pilih salah satu.
- Jawab ringkas (2–4 paragraf).
- Jika info tidak pasti katakan "sekitar" atau sarankan cek langsung.
BATASAN KETAT:
- Jika ada pertanyaan di luar topik, HARUS redirect
- JANGAN mengarang fakta.
PROMPT
];

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input)) {
 respond(400, ['error' => 'Invalid JSON body.']);
}

$message = strip_tags(trim($input['message'] ?? ''));
$topic = preg_replace('/[^a-z0-9\-]/', '', strtolower($input['topic'] ?? ''));

if (empty($message)) {
 respond(400, ['error' => 'Message cannot empty.']);
}
if (mb_strlen($message) > MAX_MESSAGE_LENGTH) {
 respond(400, ['error' => 'Message too long. Max ' . MAX_MESSAGE_LENGTH . ' character.']);
}
if (!isset($system_prompts[$topic])) {
 $validTopics = implode(', ', array_keys($system_prompts));
 respond(400, ['error' => "Topic '$topic' is not available. Topic available: $validTopics."]);
}

$rawHistory = is_array($input['history'] ?? null) ? $input['history'] : [];
$rawHistory = array_slice($rawHistory, 0, -1);
$rawHistory = array_slice($rawHistory, -(MAX_HISTORY_PAIRS * 2));

$history = [];
foreach ($rawHistory as $entry) {
 if (
  !is_array($entry) ||
  !isset($entry['role'], $entry['content']) ||
  !in_array($entry['role'], ['user', 'assistant'], true) ||
  !is_string($entry['content']) ||
  trim($entry['content']) === ''
 ) continue;

 $history[] = [
  'role' => $entry['role'],
  'content' => mb_substr(strip_tags(trim($entry['content'])), 0, 1000)
 ];
}

$messages = array_merge(
 [['role' => 'system', 'content' => $system_prompts[$topic]]],
 $history,
 [['role' => 'user', 'content' => $message]]
);

$payload = [
 'model' => GROQ_MODEL,
 'max_tokens' => MAX_TOKENS,
 'temperature' => TEMPERATURE,
 'stream' => false,
 'messages' => $messages
];

$ch = curl_init(GROQ_ENDPOINT);
curl_setopt_array($ch, [
 CURLOPT_RETURNTRANSFER => true,
 CURLOPT_POST => true,
 CURLOPT_POSTFIELDS => json_encode($payload),
 CURLOPT_HTTPHEADER => [
  'Authorization: Bearer ' . GROQ_API,
  'Content-Type: application/json'
 ],
 CURLOPT_TIMEOUT => CURL_TIMEOUT
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_errno($ch);
$curl_msg = curl_error($ch);
curl_close($ch);

if ($curl_err !== 0) {
 respond(503, array_merge(
  ['success' => false, 'error' => 'Failed to connect Groq AI, try again.'],
  DEV_MODE ? ['curl_error' => $curl_msg] : []
 ));
}

$result = json_decode($response, true);

if ($http_code === 200 && isset($result['choices'][0]['message']['content'])) {
 respond(200, [
  'success' => true,
  'reply' => trim($result['choices'][0]['message']['content']),
  'model' => GROQ_MODEL,
  'tokens' => $result['usage']['total_tokens'] ?? 0,
  'topic' => ucfirst($topic)
 ]);
} else {
 $errorMsg = $result['error']['message'] ?? 'Unknown error from Groq AI.';
 respond($http_code ?: 502, array_merge(
  ['success' => false, 'error' => "HTTP $http_code: $errorMsg"],
  DEV_MODE ? ['raw' => $result] : []
 ));
}