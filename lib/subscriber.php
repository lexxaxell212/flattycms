<?php
function getSubscriberByEmail($email)
{
  $pdo = $GLOBALS["pdo"];
  $stmt = $pdo->prepare(
    "SELECT id, email FROM subscribers WHERE email = ? AND status = 'active'"
  );
  $stmt->execute([$email]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function unsubscribeById($id)
{
  $pdo = $GLOBALS["pdo"];
  $update = $pdo->prepare(
    "UPDATE subscribers SET status = 'unsubscribed', unsubscribed_at = NOW(), unsubscribe_token = NULL, token_expires = NULL WHERE id = ?"
  );
  return $update->execute([$id]);
}

function getSubscriberByToken($token)
{
  $pdo = $GLOBALS["pdo"];
  $stmt = $pdo->prepare(
    "SELECT id, email, token_expires FROM subscribers WHERE unsubscribe_token = ? AND status = 'active'"
  );
  $stmt->execute([$token]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function generateUnsubscribeToken($subscriber_id)
{
  $pdo = $GLOBALS["pdo"];
  $token = bin2hex(random_bytes(32));
  $expires = date("Y-m-d H:i:s", strtotime("+5 years"));
  $stmt = $pdo->prepare(
    "UPDATE subscribers SET unsubscribe_token = ?, token_expires = ? WHERE id = ?"
  );
  $stmt->execute([$token, $expires, $subscriber_id]);
  return $token;
}
