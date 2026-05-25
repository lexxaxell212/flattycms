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