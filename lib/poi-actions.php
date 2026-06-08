<?php
function get_poi_categories() {
    $pdo = $GLOBALS['pdo'];
    return $pdo->query("SELECT * FROM poi_categories ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
}
function get_all_poi($active_only = false) {
    $pdo   = $GLOBALS['pdo'];
    $where = $active_only ? "WHERE p.is_active = 1" : "";
    return $pdo->query("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        {$where}
        ORDER BY p.name ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
}
function get_poi_by_id($id) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        WHERE p.id = ?
    ");
    $stmt->execute([(int)$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function get_poi_by_slug($slug) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        WHERE p.slug = ? AND p.is_active = 1
    ");
    $stmt->execute([$slug]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function get_poi_by_category($category_id) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        WHERE p.category_id = ? AND p.is_active = 1
        ORDER BY p.name ASC
    ");
    $stmt->execute([(int)$category_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function generate_poi_slug($name, $exclude_id = null) {
    $pdo       = $GLOBALS['pdo'];
    $base_slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($name)));
    $base_slug = trim($base_slug, '-');
    $slug      = $base_slug;
    $counter   = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT id FROM poi WHERE slug = ?" . ($exclude_id ? " AND id != ?" : ""));
        $params = $exclude_id ? [$slug, (int)$exclude_id] : [$slug];
        $stmt->execute($params);
        if (!$stmt->fetch()) break;
        $slug = $base_slug . '-' . $counter++;
    }
    return $slug;
}
function add_poi($data) {
    $pdo  = $GLOBALS['pdo'];
    $name = trim($data['name'] ?? '');
    $cat  = (int)($data['category_id'] ?? 0);
    $lat  = (float)($data['latitude'] ?? 0);
    $lng  = (float)($data['longitude'] ?? 0);
    if (!$name || !$cat || !$lat || !$lng) return false;
    $slug = generate_poi_slug($name);
    $stmt = $pdo->prepare("
        INSERT INTO poi (category_id, name, slug, description, address, latitude, longitude, is_active, poi_url, poi_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $cat, $name, $slug,
        trim($data['description'] ?? '') ?: null,
        trim($data['address'] ?? '') ?: null,
        $lat, $lng,
        isset($data['is_active']) ? (int)$data['is_active'] : 1,
        trim($data['poi_url'] ?? '') ?: null,
        trim($data['poi_image'] ?? '') ?: null,
    ]);
    return (int)$pdo->lastInsertId();
}
function update_poi($id, $data) {
    $pdo  = $GLOBALS['pdo'];
    $id   = (int)$id;
    $name = trim($data['name'] ?? '');
    $cat  = (int)($data['category_id'] ?? 0);
    $lat  = (float)($data['latitude'] ?? 0);
    $lng  = (float)($data['longitude'] ?? 0);
    if (!$name || !$cat || !$lat || !$lng) return false;
    $slug = generate_poi_slug($name, $id);
    $stmt = $pdo->prepare("
        UPDATE poi SET
            category_id = ?, name = ?, slug = ?, description = ?,
            address = ?, latitude = ?, longitude = ?, poi_url = ?, is_active = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $cat, $name, $slug,
        trim($data['description'] ?? '') ?: null,
        trim($data['address'] ?? '') ?: null,
        $lat, $lng,
        trim($data['poi_url'] ?? '') ?: null,
        isset($data['is_active']) ? (int)$data['is_active'] : 1,
        $id,
    ]);
    return $stmt->rowCount() >= 0;
}
function update_poi_image($id, $image_path) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("UPDATE poi SET poi_image = ? WHERE id = ?");
    $stmt->execute([$image_path, (int)$id]);
    return $stmt->rowCount() > 0;
}
function update_poi_url($id, $url) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("UPDATE poi SET poi_url = ? WHERE id = ?");
    $stmt->execute([trim($url) ?: null, (int)$id]);
    return $stmt->rowCount() > 0;
}
function delete_poi($id) {
    $pdo  = $GLOBALS['pdo'];
    $id   = (int)$id;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM trip_items WHERE poi_id = ?");
    $stmt->execute([$id]);
    if ((int)$stmt->fetchColumn() > 0) return false;
    $stmt = $pdo->prepare("DELETE FROM poi WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}
function toggle_poi_status($id) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("UPDATE poi SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([(int)$id]);
    return $stmt->rowCount() > 0;
}

function get_poi_by_url($poi_url) {
    $pdo  = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
        FROM poi p
        JOIN poi_categories c ON c.id = p.category_id
        WHERE p.poi_url = ? AND p.is_active = 1
    ");
    $stmt->execute([$poi_url]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}