<?php
function generateStaticPage($slug, $html_content, $meta = []) {
    $dir = '../../pages/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $slug_dir = $dir . $slug . '/';
    if (!is_dir($slug_dir)) mkdir($slug_dir, 0755, true);

    // Meta defaults
    $meta_title       = addslashes($meta['title']       ?? $slug);
    $meta_desc        = addslashes($meta['description'] ?? '');
    $meta_keywords    = addslashes($meta['keywords']    ?? '');
    $meta_og_image    = addslashes($meta['og_image']    ?? '');
    $meta_canonical   = addslashes($meta['canonical']   ?? "https://ayokebandung.id/pages/{$slug}/");
    $meta_robots      = addslashes($meta['robots']      ?? 'index, follow');

    $depth            = substr_count(trim($slug, '/'), '/') + 2;
    $root_path        = str_repeat('../', $depth);

    $content = <<<PHP
<?php

define('PAGE_SLUG', '{$slug}');
define('ROOT_PATH', __DIR__ . '/{$root_path}');

require_once ROOT_PATH . 'lib/functions.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Primary Meta -->
  <title>{$meta_title}</title>
  <meta name="description" content="{$meta_desc}">
  <meta name="keywords"    content="{$meta_keywords}">
  <meta name="robots"      content="{$meta_robots}">
  <link rel="canonical"    href="{$meta_canonical}">

  <meta property="og:title"       content="{$meta_title}">
  <meta property="og:description" content="{$meta_desc}">
  <meta property="og:url"         content="{$meta_canonical}">
  <meta property="og:type"        content="website">
  <meta property="og:image"       content="{$meta_og_image}">

  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="{$meta_title}">
  <meta name="twitter:description" content="{$meta_desc}">
  <meta name="twitter:image"       content="{$meta_og_image}">

  <?php include ROOT_PATH . 'lib/head-assets.php'; ?>
</head>
<body>

<?php include ROOT_PATH . 'lib/header.php'; ?>

<main id="page-content">
{$html_content}
</main>

<?php include ROOT_PATH . 'lib/footer.php'; ?>

<?php include ROOT_PATH . 'lib/foot-assets.php'; // js, analytics, dll ?>

</body>
</html>
PHP;

    file_put_contents($slug_dir . 'index.php', $content);

    // Clean URL .htaccess
    file_put_contents($slug_dir . '.htaccess',
        "RewriteEngine On\nRewriteRule ^$ index.php [L]\n"
    );

    return $slug_dir . 'index.php';
}