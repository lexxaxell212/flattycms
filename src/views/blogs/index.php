<?php
$page_title = "Blogs";

require_once LIB_PATH . "blogs.php";

$id = (int) ($_GET["id"] ?? 0);
$cat_id = (int) ($_GET["cat"] ?? 0);
$page = max(1, (int) ($_GET["page"] ?? 1));
$per_page = 9;
$offset = ($page - 1) * $per_page;

$total_pages = 1;
if ($id > 0) {
  $view_key = "post_view_{$id}";
  $post = safe_get_post($pdo, $id);

  if ($post && !isset($_SESSION[$view_key])) {
    $pdo
      ->prepare("UPDATE allcontent_posts SET views = views + 1 WHERE id = ?")
      ->execute([$id]);
    $_SESSION[$view_key] = true;
    $post = safe_get_post($pdo, $id);
  }

  $posts = [];
  $total = 0;
  $page_title = htmlspecialchars($post["title"]) . " — Blog";
} else {
  $post = null;
  $total = safe_count_posts($pdo, $cat_id);
  $posts = safe_get_posts($pdo, $per_page, $offset, $cat_id);
  $total_pages = (int) ceil($total / $per_page);
  $page_title = $cat_id > 0 ? "Kategori Blog" : "Blog";
}

$categories = safe_get_categories($pdo);
?>

<main id="content" class="container-fluid">
<div class="container">

<?php if ($post):// ══════════ SINGLE POST VIEW ══════════
   ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3 text-muted
            text-decoration-none align-items-center">
                <ol class="breadcrumb small text-muted text-decoration-none
                align-items-center">
                    <li class="breadcrumb-item text-muted small"><a
                    class="text-decoration-none"
                    href="/blogs/">Blogs</a></li>
                    <?php if (!empty($post["cat_name"])): ?>
                    <li class="breadcrumb-item text-muted small">
                        <a class="text-decoration-none" href="/blogs/?cat=<?= (int) ($post[
                          "category_id"
                        ] ?? 0) ?>">
                            <?= htmlspecialchars(
                              $post["cat_name"],
                              ENT_QUOTES,
                              "UTF-8",
                            ) ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active text-muted small" aria-current="page">
                        <?= htmlspecialchars(
                          mb_substr($post["title"] ?? "", 0, 50),
                          ENT_QUOTES,
                          "UTF-8",
                        ) ?>
                    </li>
                </ol>
            </nav>

            <!-- Judul -->
            <h4 class="h4 mb-5" style="display:none">
                <?= htmlspecialchars(
                  $post["title"] ?? "",
                  ENT_QUOTES,
                  "UTF-8",
                ) ?>
            </h4>

            <!-- Meta -->
            <div class="text-muted small mb-4 d-flex flex-wrap align-items-center gap-2">
                <?php if (
                  !empty($post["views"]) &&
                  (int) $post["views"] > 0
                ): ?> 
                    <span class="badge text-muted"><i class="fas fa-eye mr-1"></i><?= number_format(
                      (int) $post["views"],
                    ) ?></span>
                <span><?= fmt_date($post["created_at"] ?? "") ?></span>
                <?php endif; ?>
                <?php if (!empty($post["cat_name"])): ?>
                    <a href="/blogs/?cat=<?= (int) ($post["category_id"] ??
                      0) ?>"
                       class="me-4 badge bg-gray text-decoration-none">
                        <?= htmlspecialchars(
                          $post["cat_name"],
                          ENT_QUOTES,
                          "UTF-8",
                        ) ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Konten: sanitize XSS + fix image path -->
            <div class="post-content">
                <?= sanitize_content(
                  $post["content"] ?? "",
                  $post["title"] ?? "",
                ) ?>
            </div>

            <hr class="my-5">
            
            <!-- Reaction Button -->
            <?php
            $stmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM reactions WHERE content_type='blog' AND content_id=?");
            $stmt->execute([$id]);
            $reaction_count = $stmt->fetchColumn();
            
            $user_liked = false;
            if (isset($_SESSION['user'])) {
                $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM reactions WHERE user_id=? AND content_type='blog' AND content_id=?");
                $stmt->execute([$_SESSION['user']['id'], $id]);
                $user_liked = (bool) $stmt->fetch();
            }
            ?>
            <div class="d-flex align-items-center gap-2 mb-4">
                <!-- Reaction -->
                <button 
                    id="btn-reaction"
                    class="btn btn-sm <?= $user_liked ? 'btn-primary' :
                    'btn-outline-primary' ?>"
                    data-id="<?= $id ?>">
                    <i class="fas fa-heart me-1"></i>
                    <span id="reaction-count"><?= $reaction_count ?></span>
                </button>
            
                <!-- Share -->
                <?php $share_url = urlencode(BASE_URL . 'blogs/?id=' . $id); ?>
                <?php $share_title = urlencode($post['title'] ?? ''); ?>
                
                <a href="https://wa.me/?text=<?= $share_title ?>%20<?= $share_url ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fab fa-whatsapp"></i>
                </a>
            
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fab fa-facebook-f"></i>
                </a>
            
                <button onclick="copyLink()"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fab fa-instagram"></i>
                </button>
            </div>
            
            <a href="/blogs/<?= $cat_id > 0 ? "?cat=" . $cat_id : "" ?>"
               class="btn btn-sm btn-primary">
                <i class="fas fa-angle-left me-1"></i>Kembali
            </a>

        </div>
    </div>

<?php // ══════════ BLOG LIST VIEW ══════════
  // tampilkan nama kategori aktif

  else: ?>

    <div class="row g-4">

        <!-- ── Kiri: daftar post ── -->
        <div class="col-md-8">

            <div class="mb-4 d-flex align-items-center justify-content-between">
                <h5 class="mb-4">
                    <?php if ($cat_id > 0):
                      $active_cats = array_filter(
                        $categories,
                        fn($c) => (int) $c["id"] === $cat_id,
                      );
                      $active_cat = reset($active_cats);
                      echo "Kategori : " .
                        htmlspecialchars(
                          $active_cat["name"] ?? "",
                          ENT_QUOTES,
                          "UTF-8",
                        );
                    else:
                      echo "Daftar Blog";
                    endif; ?>
                </h5>
                
                <?php if ($cat_id > 0): ?>
                    <a href="/blogs/" class="px-4 py-2 rounded-md
                    text-decoration-none bg-gray">
                        Semua Blog
                    </a>
                <?php endif; ?>
               
            </div>

            <?php if (empty($posts)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fs-1 d-block mb-3 opacity-50"></i>
                    <p class="mb-0">Belum ada artikel di sini.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($posts as $p): ?>
            
            <div class="card card-glass shadow-md mb-4">
             
                <div class="card-body">
                 
                    <a href="/blogs/?id=<?= (int) $p[
                      "id"
                    ] ?>" class="h5 text-decoration-none">
                        <?= htmlspecialchars(
                          $p["title"] ?? "",
                          ENT_QUOTES,
                          "UTF-8",
                        ) ?>
                    </a>
                    
                    <div class="mt-5 mb-5 d-flex align-items-center justify-content-start gap-2 small text-muted">
                     <!-- TAMBAH VIEW -->
                     <?php if (!empty($p["views"]) && (int) $p["views"] > 0): ?>
                     <span class="badge text-muted"><i class="fas fa-eye
                     mr-1"></i><?= number_format((int) $p["views"]) ?></span>
                     <span class="small"><?= fmt_date(
                       $p["created_at"] ?? "",
                     ) ?></span>
                     <?php endif; ?>
                     <?php if (!empty($p["cat_name"])): ?>
                     <a href="/blogs/?cat=<?= (int) ($p["category_id"] ?? 0) ?>"
                        class="me-4 badge bg-gray text-decoration-none small">
                         <?= htmlspecialchars(
                           $p["cat_name"],
                           ENT_QUOTES,
                           "UTF-8",
                         ) ?>
                     </a>
                     <?php endif; ?>
                 </div>

                    <!-- truncate dulu, baru escape -->
                    <p class="text-muted small mb-5">
                        <?= safe_excerpt(
                          $p["excerpt"] ?? ($p["content"] ?? ""),
                          160,
                        ) ?>
                    </p>
                    
                    <a class="text-decoration-none" href="/blogs/?id=<?= (int) $p[
                      "id"
                    ] ?>">
                        <button class="btn btn-primary btn-sm mb-4">
                         Baca Selengkapnya
                         <i class="fas fa-angle-right me-1"></i>
                       </button>
                    </a>

                </div>
            </div>
            <?php endforeach; ?>

            <!-- PAGINATION -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Navigasi halaman" class="mt-4">
                <ul class="pagination justify-content-center flex-wrap mb-2">

                    <!-- Prev -->
                    <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
                        <a class="page-link"
                           href="?<?= http_build_query(
                             array_filter([
                               "cat" => $cat_id ?: null,
                               "page" => $page - 1,
                             ]),
                           ) ?>"
                           aria-label="Halaman sebelumnya">&laquo;</a>
                    </li>

                    <!-- Nomor halaman dengan ellipsis -->
                    <?php
                    $range = 2;
                    $prev_printed = false;
                    for ($i = 1; $i <= $total_pages; $i++):

                      $in_range =
                        $i === 1 ||
                        $i === $total_pages ||
                        ($i >= $page - $range && $i <= $page + $range);
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
                           href="?<?= http_build_query(
                             array_filter([
                               "cat" => $cat_id ?: null,
                               "page" => $i,
                             ]),
                           ) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php
                    endfor;
                    ?>

                    <!-- Next -->
                    <li class="page-item <?= $page >= $total_pages
                      ? "disabled"
                      : "" ?>">
                        <a class="page-link"
                           href="?<?= http_build_query(
                             array_filter([
                               "cat" => $cat_id ?: null,
                               "page" => $page + 1,
                             ]),
                           ) ?>"
                           aria-label="Halaman berikutnya">&raquo;</a>
                    </li>

                </ul>
                <p class="text-center text-muted small mb-0">
                    Halaman <?= $page ?> dari <?= $total_pages ?>
                    &nbsp;·&nbsp; <?= number_format($total) ?> artikel
                </p>
            </nav>
            <?php endif; ?>

        </div><!-- /col-md-8 -->

        <!-- ── Kanan: sidebar kategori ── -->
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="sticky-top" style="top:80px">
                <div class="py-4 card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Kategori</h6>
                        <?php if (empty($categories)): ?>
                            <p class="text-muted small mb-0">Belum ada kategori.</p>
                        <?php endif; ?>
                        <?php foreach ($categories as $cat): ?>
                        <a href="/blogs/?cat=<?= (int) ($cat["id"] ?? 0) ?>"
                           class="d-flex justify-content-between align-items-center
                                  text-decoration-none py-2 text-muted border-bottom
                                  <?= (int) ($cat["id"] ?? 0) === $cat_id
                                    ? "fw-bold"
                                    : "text-body" ?>">
                            <span><?= htmlspecialchars(
                              $cat["name"] ?? "",
                              ENT_QUOTES,
                              "UTF-8",
                            ) ?></span>
                            <span class="badge bg-gray rounded-pill">
                                <?= (int) ($cat["post_count"] ?? 0) ?>
                            </span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php endif; ?>

</div>
</main>