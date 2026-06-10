<?php
$page_title = "Blog";
require_once LIB_PATH . "blogs.php";

$slug   = trim($_GET['slug'] ?? '');
$id     = (int)($_GET['id'] ?? 0);
$cat_id = (int)($_GET['cat'] ?? 0);
$page   = max(1, (int)($_GET['page'] ?? 1));
$per_page = 9;
$offset = ($page - 1) * $per_page;
$total_pages = 1;

if ($slug) {
    $post = get_post_by_slug($pdo, $slug);
    $id   = $post['id'] ?? 0;
    if ($post) {
        $view_key = "post_view_{$id}";
        if (!isset($_SESSION[$view_key])) {
            $pdo->prepare("UPDATE allcontent_posts SET views = views + 1 WHERE id = ?")
                ->execute([$id]);
            $_SESSION[$view_key] = true;
            $post = get_post_by_slug($pdo, $slug);
        }
    }
    $posts = [];
    $total = 0;
    $page_title = htmlspecialchars($post['title'] ?? 'Blog') . ' - Blog';
} elseif ($id > 0) {
    $view_key = "post_view_{$id}";
    $post = safe_get_post($pdo, $id);
    if ($post && !isset($_SESSION[$view_key])) {
        $pdo->prepare("UPDATE allcontent_posts SET views = views + 1 WHERE id = ?")
            ->execute([$id]);
        $_SESSION[$view_key] = true;
        $post = safe_get_post($pdo, $id);
    }
    $posts = [];
    $total = 0;
    $page_title = htmlspecialchars($post['title'] ?? 'Blog') . ' — Blog';
} else {
    $post  = null;
    $total = safe_count_posts($pdo, $cat_id);
    $posts = safe_get_posts($pdo, $per_page, $offset, $cat_id);
    $total_pages = (int)ceil($total / $per_page);
    $page_title = $cat_id > 0 ? 'Kategori Blog' : 'Blog';
}

$categories = safe_get_categories($pdo);
?>
<script src="<?= JS_URL ?>reactions.js" defer></script>
<main class="main-content">
<div class="container">
<?php if ($post): ?>
    <div class="row justify-content-center">
        <div class="col-lg">
          <section>
            <nav aria-label="breadcrumb" class="mb-1 text-muted
            text-decoration-none align-items-center">
                <ol class="breadcrumb small text-muted text-decoration-none
                align-items-center">
                    <li class="breadcrumb-item text-muted small">
                    class="text-decoration-none"
                    href="/blogs/">Blogs</a></li>
                    <?php if (!empty($post["cat_name"])): ?>
                    <li class="breadcrumb-item text-muted small">
                        <a class="text-decoration-none" href="/blogs/?cat=<?= (int) ($post["category_id"] ?? 0) ?>">
                            <?= htmlspecialchars($post["cat_name"], ENT_QUOTES, "UTF-8") ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active text-muted small" aria-current="page">
                        <?= htmlspecialchars(mb_substr($post["title"] ?? "", 0, 70), ENT_QUOTES, "UTF-8") ?>
                    </li>
                </ol>
            </nav>
            <h2 class="mb-5" style="display:none">
                <?= htmlspecialchars($post["title"] ?? "", ENT_QUOTES, "UTF-8") ?>
            </h2>
            <div class="text-muted small d-flex flex-wrap align-items-center gap-2 mb-4">
                <?php if (!empty($post["views"]) && (int) $post["views"] > 0): ?> 
                    <span class="badge text-muted"><i class="fas fa-eye me-2"></i><?= number_format((int) $post["views"]) ?></span>
                <span><?= fmt_date($post["created_at"] ?? "") ?></span>
                <?php endif; ?>
                <?php if (!empty($post["cat_name"])): ?>
                    <a href="/blogs/?cat=<?= (int) ($post["category_id"] ?? 0) ?>"
                       class="badge bg-gray text-decoration-none">
                        <?= htmlspecialchars($post["cat_name"], ENT_QUOTES, "UTF-8") ?>
                    </a>
                <?php endif; ?>
            </div>
            <article class="post-content">
                <?= sanitize_content($post["content"] ?? "", $post["title"] ?? "") ?>
            </article>
            <hr class="my-5">
            <?php
            require_once LIB_PATH . "v-reactions.php";
            ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="justify-content-center">
                <div class="mb-2 text-center">
                  <span class="text-muted">Beri like</span>
                </div>
              <div class="align-items-center">
                <button 
                    id="btn-reaction"
                    class="btn <?= $user_liked ? 'btn-primary btn-sm' : 'btn-outline-primary btn-sm' ?>" data-id="<?= $id ?>" data-type="blog">
                    <i class="fas fa-heart me-1"></i>
                    <span id="reaction-count"><?= $reaction_count ?></span>
                </button>
                </div>
            </div>
              <div class="justify-content-center">
                <div class="mb-2 text-center">
                  <span class="text-muted">Bagikan artikel ini</span>
                </div>
              <div class="gap-2 align-items-center">
              <?php $share_url = urlencode(BASE_URL . 'blogs/?slug=' . ($post['slug'] ?? '')); ?>
                <?php $share_title = urlencode($post['title'] ?? ''); ?>
                <a href="https://wa.me/?text=<?= $share_title ?>%20<?= $share_url ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <button onclick="copyLink()"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fa-brands fa-instagram"></i>
                </button>
                </div>
              </div>
            </div>
            <a href="/blogs/<?= $cat_id > 0 ? "?cat=" . $cat_id : "" ?>"
               class="btn btn-primary">
                <i class="fas fa-angle-left me-1"></i>Kembali
            </a>
          </section>
        </div>
    </div>
<?php else: ?>
      <section class="row g-4">
        <div class="col-md-7">
          
            <div class="d-flex align-items-center justify-content-between">
              
                <h2>
                    <?php if ($cat_id > 0):
                      $active_cats = array_filter($categories, fn($c) => (int) $c["id"] === $cat_id);
                      $active_cat = reset($active_cats);
                      echo "Kategori : " . htmlspecialchars($active_cat["name"] ?? "", ENT_QUOTES, "UTF-8");
                    else:
                      echo "Daftar Blog";
                    endif; ?>
                </h2>
                
                <?php if ($cat_id > 0): ?>
                    <a href="/blogs/">
                        Semua Blog
                    </a>
                <?php endif; ?>
               
            </div>
            <?php if (!$cat_id): ?>
            <p class="lead mb-4">Kumpulan artikel pilihan seputar wisata, kuliner, dan inspirasi perjalanan.</p>
            <?php endif; ?>

            <?php if (empty($posts)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                    <p class="mb-0">Belum ada artikel di sini.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($posts as $p): ?>
            
            <article class="card card-flatty mb-4">
                <div class="card-body">
                    <a href="/blogs/?slug=<?= htmlspecialchars($p['slug'] ?? '') ?>" class="h5 mb-2">
                      <?= htmlspecialchars(mb_strlen($p['title'] ?? '') > 60 ?
                                        mb_substr($p['title'], 0, 60) . '…' : ($p['title'] ?? ''),
                                        ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <div class="d-flex align-items-center justify-content-start gap-2 small text-muted mb-3">
                     <!-- TAMBAH VIEW -->
                     <?php if (!empty($p["views"]) && (int) $p["views"] > 0): ?>
                     <span class="text-muted"><i class="fas fa-eye me-2"></i><?= number_format((int) $p["views"]) ?></span>
                     <span class="small"><?= fmt_date($p["created_at"] ?? "") ?></span>
                     <?php endif; ?>
                     <?php if (!empty($p["cat_name"])): ?>
                     <a href="/blogs/?cat=<?= (int) ($p["category_id"] ?? 0) ?>"
                        class="me-4 badge bg-gray text-decoration-none small">
                         <?= htmlspecialchars($p["cat_name"], ENT_QUOTES, "UTF-8") ?>
                     </a>
                     <?php endif; ?>
                 </div>
                    <p class="text-muted small">
                        <?= safe_excerpt($p["excerpt"] ?? ($p["content"] ?? ""), 160) ?>
                    </p>
                </div>
                <div class="card-footer">
                      <a href="/blogs/?slug=<?= htmlspecialchars($p['slug'] ?? '') ?>" class="btn btn-primary btn-sm">
                         Baca Selengkapnya
                         <i class="arrow-icon fas fa-angle-right me-1"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>

            <!-- PAGINATION -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Navigasi halaman" class="mt-4">
                <ul class="pagination justify-content-center flex-wrap mb-4">

                    <!-- Prev -->
                    <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
                        <a class="page-link"
                           href="?<?= http_build_query(array_filter(["cat" => $cat_id ?: null, "page" => $page - 1])) ?>"
                           aria-label="Halaman sebelumnya">&laquo;</a>
                    </li>

                    <!-- Nomor halaman dengan ellipsis -->
                    <?php
                    $range = 2;
                    $prev_printed = false;
                    for ($i = 1; $i <= $total_pages; $i++):
                      $in_range = $i === 1 || $i === $total_pages || ($i >= $page - $range && $i <= $page + $range);
                      if (!$in_range):
                        if (!$prev_printed) {
                          echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                          $prev_printed = true;
                        }
                        continue;
                      endif;
                      $prev_printed = false;
                      ?>
                    <li class="page-item <?= $i === $page ? "active" : "" ?>"
                        <?= $i === $page ? 'aria-current="page"' : "" ?>>
                        <a class="page-link"
                           href="?<?= http_build_query(array_filter(["cat" => $cat_id ?: null, "page" => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?= $page >= $total_pages ? "disabled" : "" ?>">
                        <a class="page-link"
                           href="?<?= http_build_query(array_filter(["cat" => $cat_id ?: null, "page" => $page + 1])) ?>"
                           aria-label="Halaman berikutnya">&raquo;</a>
                    </li>

                </ul>
                <p class="text-center text-muted small mb-0">
                    Halaman <?= $page ?> dari <?= $total_pages ?>
                    &nbsp;·&nbsp; <?= number_format($total) ?> artikel
                </p>
            </nav>
            <?php endif; ?>

        </div>
        <div class="col-md-5">
          <div style="position:sticky !important; top:82px; align-self:flex-start;">
                <div class="cat-panel">
                        <h5 class="mb-3">Kategori</h5>
                        <?php if (empty($categories)): ?>
                            <p class="text-muted small mb-0">Belum ada kategori.</p>
                        <?php endif; ?>
                        <?php foreach ($categories as $cat): ?>
                        <a href="/blogs/?cat=<?= (int) ($cat["id"] ?? 0) ?>"
                           class="d-flex justify-content-between
                           align-items-center py-2 border-bottom cat-link <?= (int)
                           ($cat["id"] ?? 0) === $cat_id ? "fw-bold" : "text-body" ?>">
                            <span><?= htmlspecialchars($cat["name"] ?? "", ENT_QUOTES, "UTF-8") ?></span>
                            <span class="badge bg-gray rounded-pill">
                                <?= (int) ($cat["post_count"] ?? 0) ?>
                            </span>
                        </a>
                        <?php endforeach; ?>
                    </div>
            </div>
        </div>
      </section>
<?php endif; ?>
</div>
</main>