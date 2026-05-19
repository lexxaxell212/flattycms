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

function generateStaticPage($slug, $html_content, $page_id, $title) {
    $pages_dir = PUBLIC_PATH . 'pages/';
    $page_dir  = $pages_dir . $slug . '/';
    $page_title_val = addslashes($title);
    
            // Strip PHP tags
        $html_content = preg_replace('/<\?(?:php|=)?.*?\?>/is', '', $html_content);
        
        // Strip script tags
        $html_content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html_content);
        
        // Strip event handlers (onload, onerror, onclick, dll)
        $html_content = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']|on\w+\s*=\s*\S+/i', '', $html_content);
    

    try {
        if (!is_dir($page_dir)) mkdir($page_dir, 0755, true);

        // Template output — header/footer di-include, PHP bisa jalan
        $content = <<<PHP
<?php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
autoload_core();

\$_page_id = {$page_id};

// Reaction data
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
{$html_content}
<div class="container">
  <hr class="my-4">
  <div class="d-flex align-items-center gap-2 mb-4">
    <button id="btn-reaction"
      class="btn <?php echo \$_user_liked ? 'btn-primary' :
      'btn-outline-primary'; ?>"
      data-id="<?php echo \$_page_id; ?>">
      <i class="fas fa-heart me-1"></i>
      <span id="reaction-count"><?php echo \$_reaction_count; ?></span>
    </button>
    <?php
    \$_share_url = urlencode(BASE_URL . 'pages/{$slug}/');
    \$_share_title = urlencode('{$slug}');
    ?>
    <a href="https://wa.me/?text=<?php echo \$_share_title; ?>%20<?php echo \$_share_url; ?>"
       target="_blank" rel="noopener" class="btn btn-outline-primary">
      <i class="fab fa-whatsapp"></i>
    </a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo \$_share_url; ?>"
       target="_blank" rel="noopener" class="btn btn-outline-primary">
      <i class="fab fa-facebook-f"></i>
    </a>
    <button onclick="copyLink()" class="btn btn-outline-primary">
      <i class="fab fa-instagram"></i>
    </button>
  </div>
</div>
<?php
require_once SRC_PATH . 'footer.php';
?>
PHP;

        $result = file_put_contents($page_dir . 'index.php', $content);
        if ($result === false) return false;

        // .htaccess clean URL
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
    $dir = realpath(PUBLIC_PATH . 'pages/' . $slug);
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