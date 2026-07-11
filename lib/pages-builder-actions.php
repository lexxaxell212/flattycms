<?php
require_once ADMIN_VIEW_PATH . 'pages/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'] ?? '')) {
 $title = trim($_POST['title'] ?? '');
 $html_content = $_POST['html_content'] ?? '';
 $edit_slug = trim($_POST['edit_slug'] ?? '');
 $event_date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
 $event_date_end = !empty($_POST['event_date_end']) ? $_POST['event_date_end'] : null;

 if ($edit_slug) {
  $slug = $edit_slug;
  $saved = $pdo->prepare("UPDATE pages SET title=?, html_content=?, event_date=?, event_date_end=?, updated_at=NOW() WHERE slug=?")
  ->execute([$title, $html_content, $event_date, $event_date_end, $slug]);
  $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
  $stmt->execute([$slug]);
  $page_id = (int) $stmt->fetchColumn();
 } else {
  $base_slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower($title));
  $slug = generateUniqueSlug($pdo, $base_slug);
  $saved = $pdo->prepare("INSERT INTO pages (title, slug, html_content, event_date, event_date_end) VALUES (?,?,?,?,?)")
  ->execute([$title, $slug, $html_content, $event_date, $event_date_end]);
  $page_id = (int) $pdo->lastInsertId();
 }

 if ($saved && generateStaticPage($slug, $html_content, $page_id, $title)) {
  header("Location: /admin/pages-builder?edit=" . urlencode($slug) . "&saved=1");
 } else {
  header("Location: /admin/pages-builder?error=1");
 }
 exit;
}

if (isset($_GET['delete']) && verify_csrf_token($_GET['csrf_delete'] ?? '')) {
 $stmt = $pdo->prepare("SELECT slug FROM pages WHERE slug = ?");
 $stmt->execute([$_GET['delete']]);
 $page_to_delete = $stmt->fetch();
 if ($page_to_delete) {
  deletePageFiles($page_to_delete['slug']);
  $pdo->prepare("DELETE FROM pages WHERE slug = ?")->execute([$_GET['delete']]);
 }
 header('Location: /admin/pages-builder?deleted=1');
 exit;
}