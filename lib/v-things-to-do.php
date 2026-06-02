<?php
$kuliner_items = $GLOBALS['pdo']->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('kuliner') ORDER BY id DESC")->fetchAll();

$budaya_items  = $GLOBALS['pdo']->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('wisata_budaya') ORDER BY id DESC")->fetchAll();

function _tdo_excerpt($html, $limit = 150) {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '…' : $text;
}