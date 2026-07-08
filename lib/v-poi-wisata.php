<?php
require_once LIB_PATH . 'poi-actions.php';

$all_poi = get_all_poi(true);

$wisata_poi = array_filter($all_poi, function($item) {
  return $item['category_slug'] === 'wisata';
});

shuffle($wisata_poi);
$wisata_poi = array_slice($wisata_poi, 0, 6);

$GLOBALS['wisata_poi'] = $wisata_poi;