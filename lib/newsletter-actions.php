<?php
require_once LIB_PATH . "mailer.php";
require_once LIB_PATH . "subscriber.php";

// DELETE subscriber (POST + CSRF)
if (
  $_SERVER["REQUEST_METHOD"] === "POST" &&
  isset($_POST["delete_subscriber"])
) {
  validate_csrf();
  $id = (int) $_POST["id"];
  if ($id > 0) {
    $pdo
      ->prepare("UPDATE subscribers SET status = 'deleted' WHERE id = ?")
      ->execute([$id]);
  }
  regenerate_csrf_token();
  header("Location: /admin/newsletter#subscribers");
  exit();
}

// KIRIM NEWSLETTER
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["send_newsletter"])) {
  validate_csrf();

  $subject = trim($_POST["subject"] ?? "");
  $message = trim($_POST["message"] ?? "");

  if ($subject && $message) {
    set_time_limit(0);
    session_write_close();

    $pdo
      ->prepare(
        "INSERT INTO newsletters (subject, message, total_recipients, status) VALUES (?, ?, 0, 'draft')",
      )
      ->execute([$subject, $message]);
    $newsletter_id = $pdo->lastInsertId();

    $subs = $pdo
      ->query('SELECT id, email FROM subscribers WHERE status = "active"')
      ->fetchAll();
    $sent = 0;

    foreach ($subs as $sub) {
      $token = generateUnsubscribeToken($sub["id"]);
      $unsub = "https://ayokebandung.id/pages/unsubscribe?token=" . $token;
      $html = newsletter_template($subject, $message, $unsub);
      if (kirimEmailAyo($sub["email"], $subject, $html)) {
        $sent++;
      }
      usleep(100000);
    }

    $total = count($subs);
    $pdo
      ->prepare(
        "UPDATE newsletters SET total_recipients=?, sent_recipients=?, status='sent', sent_at=NOW() WHERE id=?",
      )
      ->execute([$total, $sent, $newsletter_id]);

    // Buka session lagi untuk flash message
    session_start([
      "cookie_httponly" => true,
      "cookie_secure" => isset($_SERVER["HTTPS"]),
      "cookie_samesite" => "Strict",
    ]);
    $_SESSION[
      "nl_success"
    ] = "Newsletter terkirim ke <strong>$sent/$total</strong> orang!";
    header("Location: /admin/newsletter");
    exit();
  }
}

function newsletter_template(
  string $subject,
  string $message,
  string $unsub
): string {
  $subject = htmlspecialchars($subject);
  $message = nl2br(htmlspecialchars($message));
  $date = date("d M Y, H:i");
  $year = date("Y");
  return <<<HTML
  <!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"></head>
  <body style="margin:0;padding:0;background:#0f172a;font-family:sans-serif">
  <div style="max-width:600px;margin:0 auto;background:#0f172a">
      <div style="background:linear-gradient(135deg,#667eea,#764ba2);padding:40px 30px;text-align:center">
          <h1 style="color:#fff;margin:0;font-size:28px">Ayokebandung.id</h1>
          <p style="color:#e2e8f0;margin:8px 0 0">Update terbaru untuk Anda</p>
      </div>
      <div style="background:#fff;padding:40px 30px">
          <div style="border-left:4px solid #667eea;padding-left:20px;margin-bottom:30px">
              <h2 style="color:#1e293b;margin:0">$subject</h2>
              <p style="color:#64748b;margin:8px 0 0;font-size:14px">Dikirim pada $date</p>
          </div>
          <div style="line-height:1.7;color:#1e293b;font-size:16px;margin-bottom:30px">$message</div>
          <div style="text-align:center;margin:30px 0">
              <a href="https://ayokebandung.id" style="padding:16px 32px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;text-decoration:none;border-radius:12px;font-weight:600">Lihat Sekarang</a>
          </div>
      </div>
      <div style="background:#1e293b;padding:30px;color:#94a3b3;font-size:13px;text-align:center">
          <p>$year Ayokebandung.id</p>
          <p><a href="$unsub" style="color:#60a5fa">Berhenti berlangganan</a></p>
      </div>
  </div>
  </body></html>
  HTML;
}
