<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();
verify_ajax_request('GET');

header('Content-Type: application/json; charset=utf-8');

$category = $_GET['category'] ?? null;

$sql = "SELECT id, name, description, lat, lng, icon, distance, category FROM locations WHERE status='active'";
if ($category) {
    $stmt = $pdo->prepare($sql . " AND category = ?");
    $stmt->execute([$category]);
} else {
    $stmt = $pdo->query($sql);
}

echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);