<?php
require_once ADMIN_VIEW_PATH . 'pages/functions.php';
// SAVE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $title        = trim($_POST['title'] ?? '');
    $html_content = $_POST['html_content'] ?? '';
    $edit_slug    = trim($_POST['edit_slug'] ?? '');
    if ($edit_slug) {
        $slug  = $edit_slug;
        $saved = $pdo->prepare("UPDATE pages SET title=?, html_content=?, updated_at=NOW() WHERE slug=?")
                     ->execute([$title, $html_content, $slug]);
        $stmt  = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $page_id = (int) $stmt->fetchColumn();
    } else {
        $base_slug = strtolower(preg_replace('/[^a-z0-9-]+/', '-', $title));
        $slug      = generateUniqueSlug($pdo, $base_slug);
        $saved     = $pdo->prepare("INSERT INTO pages (title, slug, html_content) VALUES (?,?,?)")
                         ->execute([$title, $slug, $html_content]);
        $page_id   = (int) $pdo->lastInsertId();
    }
    if ($saved && generateStaticPage($slug, $html_content, $page_id, $title)) {
        header("Location: /admin/pages?edit=" . urlencode($slug) . "&saved=1");
    } else {
        header("Location: /admin/pages?error=1");
    }
    exit;
}
// DELETE
if (isset($_GET['delete']) && verify_csrf_token($_GET['csrf_delete'] ?? '')) {
    $stmt = $pdo->prepare("SELECT slug FROM pages WHERE slug = ?");
    $stmt->execute([$_GET['delete']]);
    $page_to_delete = $stmt->fetch();
    if ($page_to_delete) {
        deletePageFiles($page_to_delete['slug']);
        $pdo->prepare("DELETE FROM pages WHERE slug = ?")->execute([$_GET['delete']]);
    }
    header('Location: /admin/pages?deleted=1');
    exit;
}