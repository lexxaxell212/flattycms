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

function generateStaticPage($slug, $html_content) {
    $pages_dir = '../../pages/';
    $page_dir  = $pages_dir . $slug . '/';

    try {
        if (!is_dir($page_dir)) mkdir($page_dir, 0755, true);

        // Template output — header/footer di-include, PHP bisa jalan
        $content = <<<PHP
<?php
require_once SRC_PATH . 'header.php';
?>
{$html_content}
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
    $dir = realpath('../../pages/' . $slug);
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