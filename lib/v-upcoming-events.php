<?php
$_tdo_next_stmt = $GLOBALS['pdo']->query("
    SELECT id, title, slug, html_content, event_date
    FROM pages
    WHERE event_date IS NOT NULL
    AND event_date >= CURDATE()
    ORDER BY event_date ASC
    LIMIT 1
  ");
$_tdo_next = $_tdo_next_stmt->fetch(PDO::FETCH_ASSOC);

$_tdo_stmt = $GLOBALS['pdo']->prepare("
    SELECT id, title, slug, html_content, event_date
    FROM pages
    WHERE event_date IS NOT NULL
    And id != ?
    ORDER BY event_date ASC
    LIMIT 6
  ");
$_tdo_stmt->execute([$_tdo_next['id'] ?? 0]);
$_tdo_pages = $_tdo_stmt->fetchAll(PDO::FETCH_ASSOC);

$_tdo_past_stmt = $GLOBALS['pdo']->query("
    SELECT id, title, slug, html_content, event_date
    FROM pages
    WHERE event_date IS NOT NULL
    AND event_date < CURDATE()
    ORDER BY event_date DESC
  ");
$_tdo_past = $_tdo_past_stmt->fetchAll(PDO::FETCH_ASSOC);

function _tdo_excerpt($html, $limit = 150) {
  $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
  return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '…' : $text;
}