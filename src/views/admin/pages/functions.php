<?php
function generateUniqueSlug($pdo, $base_slug) {
    $slug    = rtrim($base_slug, '-');
    $counter = 1;
    while (true) {
        $check = $counter === 1 ? $slug : $slug . '-' . $counter;
        $stmt  = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
        $stmt->execute([$check]);
        if (!$stmt->fetch()) return $check;
        $counter++;
    }
}

function sanitizeSlug($slug) {
    return preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($slug)));
}

function sanitizeTitle($title) {
    return str_replace(["'", "\\", "\n", "\r", "\0"], ["\\'", "\\\\", '', '', ''], trim($title));
}

function sanitizeHtml($html) {
    $html = preg_replace('/<\?(?:php|=)?[\s\S]*?\?>/i', '', $html);
    $html = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
    $html = preg_replace('/(<[^>]+?)\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '$1', $html);
    $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '', $html);
    return $html;
}

function generateStaticPage($slug, $html_content, $page_id, $title) {
    $slug           = sanitizeSlug($slug);
    $page_title_val = sanitizeTitle($title);
    $html_content   = sanitizeHtml($html_content);

    $pages_dir = PUBLIC_PATH . 'pages/';
    $page_dir  = $pages_dir . $slug . '/';

    try {
        if (!is_dir($page_dir)) mkdir($page_dir, 0755, true);

        $content = <<<PHP
<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();

\$_page_id = {$page_id};

\$_r_stmt = \$GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM reactions WHERE content_type='page' AND content_id=?");
\$_r_stmt->execute([\$_page_id]);
\$_reaction_count = \$_r_stmt->fetchColumn();

\$_user_liked = false;
if (isset(\$_SESSION['user'])) {
    \$_r_stmt = \$GLOBALS['pdo']->prepare("SELECT id FROM reactions WHERE user_id=? AND content_type='page' AND content_id=?");
    \$_r_stmt->execute([\$_SESSION['user']['id'], \$_page_id]);
    \$_user_liked = (bool) \$_r_stmt->fetch();
}

\$page_title = '{$page_title_val}';

require_once SRC_PATH . 'headerv2.php';
?>
<main id="content" class="container-fluid">
  <div class="container">
    {$html_content}
    <hr class="my-4">
    <div class="d-flex align-items-center gap-2 mb-4">
      <button id="btn-reaction"
        class="btn <?php echo \$_user_liked ? 'btn-primary' : 'btn-outline-primary'; ?>"
        data-id="<?php echo \$_page_id; ?>">
        <i class="fas fa-heart me-1"></i>
        <span id="reaction-count"><?php echo \$_reaction_count; ?></span>
      </button>
      <?php
      \$_share_url   = urlencode(BASE_URL . 'pages/{$slug}/');
      \$_share_title = urlencode('{$slug}');
      ?>
      <a href="https://wa.me/?text=<?php echo \$_share_title; ?>%20<?php echo \$_share_url; ?>"
         target="_blank" rel="noopener" class="btn btn-outline-primary">
        <i class="fa-brand fa-whatsapp"></i>
      </a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo \$_share_url; ?>"
         target="_blank" rel="noopener" class="btn btn-outline-primary">
        <i class="fa-brand fa-facebook-f"></i>
      </a>
      <button onclick="copyLink()" class="btn btn-outline-primary">
        <i class="fa-brand fa-instagram"></i>
      </button>
    </div>
  </div>
</main>
<?php
require_once SRC_PATH . 'footer.php';
?>
PHP;

        $result = file_put_contents($page_dir . 'index.php', $content);
        if ($result === false) return false;

        file_put_contents($page_dir . '.htaccess', "DirectoryIndex index.php\n");

        chmod($page_dir . 'index.php', 0644);
        chmod($page_dir, 0755);

        return true;
    } catch (Exception $e) {
        error_log("generateStaticPage error: " . $e->getMessage());
        return false;
    }
}

function deletePageFiles($slug) {
    $slug = sanitizeSlug($slug);
    $dir  = realpath(PUBLIC_PATH . 'pages/' . $slug);
    if (!$dir || !is_dir($dir)) return true;
    return deleteDirectory($dir);
}

function deleteDirectory($dir) {
    if (!is_dir($dir)) return true;
    foreach (array_diff(scandir($dir), ['.', '..']) as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    return rmdir($dir);
}
