<?php
// Blogs 
function getBlogPosts($pdo, $limit = 12, $category = null, $search = null)
{
  $where = ['p.status="active"'];
  $params = [];
  if ($category) {
    $where[] = "c.slug=?";
    $params[] = $category;
  }
  if ($search) {
    $where[] = "(p.title LIKE ? OR p.content LIKE ?)";
    $params[] = "%" . $search . "%";
    $params[] = "%" . $search . "%";
  }
  $where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";
  $sql = "SELECT p.*, c.name cat_name, c.slug cat_slug 
            FROM allcontent_posts p 
            LEFT JOIN allcontent_categories c ON p.category_id = c.id 
            $where_sql 
            ORDER BY p.created_at DESC 
            LIMIT ?";
  $stmt = $pdo->prepare($sql);
  $params[] = $limit;
  $stmt->execute($params);
  return $stmt->fetchAll();
}
function getCategories($pdo)
{
  $stmt = $pdo->prepare("
        SELECT c.*, COUNT(p.id) post_count 
        FROM allcontent_categories c 
        LEFT JOIN allcontent_posts p ON c.id=p.category_id AND p.status='active' 
        GROUP BY c.id ORDER BY c.name
    ");
  $stmt->execute();
  return $stmt->fetchAll();
}
function getPost($pdo, $id)
{
  $stmt = $pdo->prepare('
        SELECT p.*, c.name cat_name, c.slug cat_slug 
        FROM allcontent_posts p 
        LEFT JOIN allcontent_categories c ON p.category_id = c.id 
        WHERE p.id = ? AND p.status = "active"
    ');
  $stmt->execute([$id]);
  $post = $stmt->fetch();
  if ($post) {
    $pdo
      ->prepare("UPDATE allcontent_posts SET views = views + 1 WHERE id = ?")
      ->execute([$id]);
  }
  return $post;
}
function getRecentPosts($pdo, $limit = 5)
{
  $stmt = $pdo->prepare('
        SELECT id, title, excerpt, created_at, views 
        FROM allcontent_posts 
        WHERE status = "active" 
        ORDER BY created_at DESC 
        LIMIT ?
    ');
  $stmt->execute([$limit]);
  return $stmt->fetchAll();
}
function safe_get_post(PDO $pdo, int $id): array|false
{
  $stmt = $pdo->prepare(
    'SELECT p.*, c.name AS cat_name, p.image_url AS image
         FROM allcontent_posts p
         LEFT JOIN allcontent_categories c ON p.category_id = c.id
         WHERE p.id = ? AND p.status = ?',
  );
  $stmt->execute([(int) $id, "active"]);
  return $stmt->fetch();
}
function safe_get_posts(
  PDO $pdo,
  int $limit,
  int $offset,
  int $cat_id = 0,
): array {
  $limit = max(1, (int) $limit);
  $offset = max(0, (int) $offset);
  $cat_id = (int) $cat_id;
  if ($cat_id > 0) {
    $sql = "
            SELECT p.id, p.title, p.excerpt, p.content, p.created_at, p.views,
            p.category_id, p.image_url AS image, c.name AS cat_name 
            FROM allcontent_posts p
            LEFT JOIN allcontent_categories c ON p.category_id = c.id
            WHERE p.status = 'active' AND p.category_id = $cat_id
            ORDER BY p.created_at DESC 
            LIMIT $limit OFFSET $offset
        ";
  } else {
    $sql = "
            SELECT p.id, p.title, p.excerpt, p.content, p.created_at, p.views,
            p.category_id, p.image_url AS image, c.name AS cat_name 
            FROM allcontent_posts p
            LEFT JOIN allcontent_categories c ON p.category_id = c.id
            WHERE p.status = 'active'
            ORDER BY p.created_at DESC 
            LIMIT $limit OFFSET $offset
        ";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function safe_count_posts(PDO $pdo, int $cat_id = 0): int
{
  if ($cat_id > 0) {
    $stmt = $pdo->prepare(
      "SELECT COUNT(*) FROM allcontent_posts WHERE status = ? AND category_id = ?",
    );
    $stmt->execute(["active", $cat_id]);
  } else {
    $stmt = $pdo->prepare(
      "SELECT COUNT(*) FROM allcontent_posts WHERE status = ?",
    );
    $stmt->execute(["active"]);
  }
  return (int) $stmt->fetchColumn();
}
function safe_get_categories(PDO $pdo): array
{
  $stmt = $pdo->prepare(
    'SELECT c.*, COUNT(p.id) AS post_count
         FROM allcontent_categories c
         LEFT JOIN allcontent_posts p ON p.category_id = c.id AND p.status = ?
         GROUP BY c.id ORDER BY c.name',
  );
  $stmt->execute(["active"]);
  return $stmt->fetchAll();
}

function get_post_by_slug(PDO $pdo, string $slug): array|false {
    $stmt = $pdo->prepare('
        SELECT p.*, c.name AS cat_name, p.image_url AS image
        FROM allcontent_posts p
        LEFT JOIN allcontent_categories c ON p.category_id = c.id
        WHERE p.slug = ? AND p.status = "active"
    ');
    $stmt->execute([$slug]);
    return $stmt->fetch();
}