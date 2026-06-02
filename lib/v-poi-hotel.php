<?php
require_once LIB_PATH . 'poi-actions.php';

$all_poi = get_all_poi(true);

$hotel_poi = array_filter($all_poi, function($item) {
    return $item['category_slug'] === 'hotel';
});

shuffle($hotel_poi);
$hotel_poi = array_slice($hotel_poi, 0, 6);

$GLOBALS['hotel_poi'] = $hotel_poi;