<?php
require_once LIB_PATH . "v-things-to-do.php";
require_once LIB_PATH . "v-kenapa.php";
require_once LIB_PATH . "blogs.php";
require_once LIB_PATH . "v-review.php";
//fluid
safe_include(SRC_PATH . "partials/part-hero.php", "Hero Section");
safe_include(SRC_PATH . "partials/part-things-to-do.php", "Things To Do Section");
safe_include(SRC_PATH . "partials/part-kenapa.php", "Kenapa Bandung");
safe_include(SRC_PATH . "partials/part-trip-planner.php", "Trip Planner");
safe_include(SRC_PATH . "partials/part-blogs.php", "Artikel Terbaru");
safe_include(SRC_PATH . "partials/part-review.php", "Cerita Komunitas");
?>