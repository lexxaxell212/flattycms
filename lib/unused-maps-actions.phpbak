<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    validate_csrf();
    $action = $_POST['action'];

    if ($action === 'add') {
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $lat         = (float)$_POST['lat'];
        $lng         = (float)$_POST['lng'];
        $icon        = trim($_POST['icon'] ?? 'fas fa-location-dot');
        $distance    = trim($_POST['distance'] ?? '');
        $category    = trim($_POST['category'] ?? 'wisata');

        if ($name && $lat && $lng) {
            $pdo->prepare("INSERT INTO locations (name, description, lat, lng, icon, distance, category) VALUES (?,?,?,?,?,?,?)")
                ->execute([$name, $description, $lat, $lng, $icon, $distance, $category]);
        }
        regenerate_csrf_token();
        header('Location: /admin/maps?saved=1');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if ($id > 0) {
            $pdo->prepare("DELETE FROM locations WHERE id=?")->execute([$id]);
        }
        regenerate_csrf_token();
        header('Location: /admin/maps?deleted=1');
        exit;
    }

    if ($action === 'toggle') {
        $id = (int)$_POST['id'];
        $pdo->prepare("UPDATE locations SET status = IF(status='active','inactive','active') WHERE id=?")->execute([$id]);
        regenerate_csrf_token();
        header('Location: /admin/maps?saved=1');
        exit;
    }
}

$locations = $pdo->query("SELECT * FROM locations ORDER BY id DESC")->fetchAll();
$msg       = isset($_GET['saved']) ? 'success' : (isset($_GET['deleted']) ? 'deleted' : null);
$csrf      = generate_csrf_token();