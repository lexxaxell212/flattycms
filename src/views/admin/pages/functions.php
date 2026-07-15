<?php
function generateUniqueSlug($pdo, $base_slug) {
 $slug = rtrim($base_slug, "-");
 $counter = 1;
 while (true) {
  $check = $counter === 1 ? $slug : $slug . "-" . $counter;
  $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
  $stmt->execute([$check]);
  if (!$stmt->fetch()) {
   return $check;
  }
  $counter++;
 }
}

function sanitizeSlug($slug) {
 return preg_replace("/[^a-z0-9\-]/", "", strtolower(trim($slug)));
}

function sanitizeTitle($title) {
 return str_replace(
  ["'", "\\", "\n", "\r", "\0"],
  ["\\'", "\\\\", "", "", ""],
  trim($title),
 );
}

function generateStaticPage($slug, $html_content, $page_id, $title) {
 $slug = sanitizeSlug($slug);
 $page_title_val = sanitizeTitle($title);
 $html_content = sanitizeHtml($html_content);

 $pages_dir = PUBLIC_PATH . "pages/";
 $page_dir = $pages_dir . $slug . "/";

 try {
  if (!is_dir($page_dir)) {
   mkdir($page_dir, 0755, true);
  }

  $content = <<<PHP
    <?php
    \$_page_id = {$page_id};
    require_once LIB_PATH . 'v-reactions-page.php';
    require_once LIB_PATH . 'v-upcoming-events.php';
    \$page_title = '{$page_title_val}';
    \$_this_event = null;
    foreach (\$_tdo_all as \$_e) {
      if (\$_e['slug'] === '{$slug}') { \$_this_event = \$_e; break; }
    }
    ?>
    <script src="<?= JS_URL ?>reactions.js" defer></script>
    <?php if (\$_this_event && \$_this_event['event_date']): ?>
    <script id="event-data" type="application/json">
    <?php echo json_encode([
      'date_range' => _tdo_date_range(\$_this_event['event_date'], \$_this_event['event_date_end'])
    ]); ?>
    </script>
    <?php endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const dataEl = document.getElementById('event-data');
      const slot = document.getElementById('event-info-slot');
      if (dataEl && slot) {
        const data = JSON.parse(dataEl.textContent);
        slot.textContent = data.date_range;
      }
    });
    </script>
    <main class="main-content">
      <div class="container">
      <div class="page-header text-center">
        <h1><em class="styled">{$page_title_val}</em></h1>
      <?php if (\$_this_event && \$_this_event['event_date']): ?>
      <div style="border:var(--border-accent-subtle);" class="badge badge-accent fw-bold mt-2">
        <i class="far fa-calendar-check me-1"></i>
        <?php echo _tdo_date_range(\$_this_event['event_date'], \$_this_event['event_date_end']); ?>
      </div>
      <?php endif; ?>
      </div>
          {$html_content}
          <hr class="my-5">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="justify-content-center">
              <div class="mb-2 text-center">
                <span class="text-muted">Beri like</span>
              </div>
              <div class="align-items-center">
                <button id="btn-reaction"
                  class="btn <?php echo \$_user_liked ? 'btn-primary btn-fit' : 'btn-outline-primary btn-fit'; ?>" data-id="<?php echo \$_page_id; ?>" data-type="page">
                  <i class="fas fa-heart me-1"></i>
                  <span id="reaction-count"><?php echo \$_reaction_count; ?></span>
                </button>
              </div>
            </div>
            <div class="justify-content-center">
              <div class="mb-2 text-center">
                <span class="text-muted">Bagikan artikel ini</span>
              </div>
              <div class="gap-2 align-items-center">
                <?php
                \$_share_url   = urlencode(BASE_URL . 'pages/{$slug}/');
                \$_share_title = urlencode('{$slug}');
                ?>
                <a href="https://wa.me/?text=<?php echo \$_share_title; ?>%20<?php echo \$_share_url; ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-outline-primary btn-fit">
                  <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo \$_share_url; ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-outline-primary btn-fit">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
                <button onclick="copyLink()"
                   class="btn btn-outline-primary btn-fit">
                  <i class="fas fa-copy"></i>
                </button>
              </div>
            </div>
          </div>
      </div>
    </main>
PHP;

    $result = file_put_contents($page_dir . "index.php", $content);
    if ($result === false) {
      return false;
    }

    file_put_contents($page_dir . ".htaccess", "DirectoryIndex index.php\n");

    chmod($page_dir . "index.php", 0644);
    chmod($page_dir, 0755);

    return true;
  } catch (Exception $e) {
    error_log("generateStaticPage error: " . $e->getMessage());
    return false;
  }
}

function deletePageFiles($slug)
{
  $slug = sanitizeSlug($slug);
  $dir = realpath(PUBLIC_PATH . "pages/" . $slug);
  if (!$dir || !is_dir($dir)) {
    return true;
  }
  return deleteDirectory($dir);
}

function deleteDirectory($dir)
{
  if (!is_dir($dir)) {
    return true;
  }
  foreach (array_diff(scandir($dir), [".", ".."]) as $file) {
    $path = $dir . "/" . $file;
    is_dir($path) ? deleteDirectory($path) : unlink($path);
  }
  return rmdir($dir);
}