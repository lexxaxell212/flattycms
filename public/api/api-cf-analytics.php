<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';
autoload_core();
verify_ajax_request('GET');

header('Content-Type: application/json; charset=utf-8');

// ── Cloudflare GraphQL Analytics ─────────────────────────
$token = CF_TOKEN;
$zone_id = CF_ZONE;

$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$week_ago = date('Y-m-d', strtotime('-7 days'));

$query = <<<GQL
{
  viewer {
    zones(filter: { zoneTag: "{$zone_id}" }) {
      today: httpRequests1dGroups(
        limit: 1
        filter: { date: "{$today}" }
      ) {
        sum { requests pageViews }
        uniq { uniques }
      }
      weekly: httpRequests1dGroups(
        limit: 7
        filter: { date_geq: "{$week_ago}", date_leq: "{$today}" }
        orderBy: [date_ASC]
      ) {
        sum { requests pageViews }
        uniq { uniques }
        dimensions { date }
      }
    }
  }
}
GQL;

$ch = curl_init('https://api.cloudflare.com/client/v4/graphql');
curl_setopt_array($ch, [
 CURLOPT_POST => true,
 CURLOPT_RETURNTRANSFER => true,
 CURLOPT_HTTPHEADER => [
  'Authorization: Bearer ' . $token,
  'Content-Type: application/json',
 ],
 CURLOPT_POSTFIELDS => json_encode(['query' => $query]),
 CURLOPT_TIMEOUT => 10,
]);

$raw = curl_exec($ch);
$errno = curl_errno($ch);
curl_close($ch);

if ($errno || !$raw) {
 http_response_code(502);
 echo json_encode(['success' => false, 'message' => 'Gagal koneksi ke Cloudflare']);
 exit;
}

$cf = json_decode($raw, true);
$zone = $cf['data']['viewer']['zones'][0] ?? null;

if (!$zone) {
 http_response_code(502);
 echo json_encode(['success' => false, 'message' => 'Data zona tidak ditemukan']);
 exit;
}

// ── Today ─────────────────────────────────────────────────
$today_data = $zone['today'][0] ?? [];
$hit_today = $today_data['sum']['requests'] ?? 0;
$uniq_today = $today_data['uniq']['uniques'] ?? 0;

// ── Weekly ────────────────────────────────────────────────
$hit_week = 0;
$uniq_week = 0;
$chart = [];

foreach ($zone['weekly'] as $day) {
 $hit_week += $day['sum']['requests'] ?? 0;
 $uniq_week += $day['uniq']['uniques'] ?? 0;
 $chart[] = [
  'date' => $day['dimensions']['date'],
  'hits' => $day['sum']['requests'] ?? 0,
  'uniques' => $day['uniq']['uniques'] ?? 0,
 ];
}

echo json_encode([
 'success' => true,
 'data' => [
  'hit_today' => $hit_today,
  'uniq_today' => $uniq_today,
  'hit_week' => $hit_week,
  'uniq_week' => $uniq_week,
  'chart' => $chart,
 ],
]);