<?php
function record_pageview(PDO $pdo): void
{
 $page = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
 $ip = $_SERVER["REMOTE_ADDR"] ?? "";
 $referrer = substr($_SERVER["HTTP_REFERER"] ?? "", 0, 500);
 $user_agent = substr($_SERVER["HTTP_USER_AGENT"] ?? "", 0, 255);

 if (empty($page)) {
  return;
 }

 if (str_starts_with($page, "/admin")) {
  return;
 }

 $bots = [
  "bot",
  "crawler",
  "spider",
  "slurp",
  "facebookexternalhit",
  "curl",
  "wget",
  "python",
  "java",
  "ruby",
  "php",
  "headless",
  "phantomjs",
  "selenium",
  "puppeteer",
  "googlebot",
  "bingbot",
  "yandex",
  "baidu",
  "duckduck",
  "ahrefsbot",
  "semrushbot",
  "mj12bot",
  "dotbot",
  "petalbot",
  "applebot",
  "twitterbot",
  "linkedinbot",
  "whatsapp",
  "telegram",
  "discord",
  "slack",
 ];
 foreach ($bots as $bot) {
  if (stripos($user_agent, $bot) !== false) {
   return;
  }
 }

 if (empty($user_agent)) {
  return;
 }

 $skip_referrers = ["android-app://",
  "com.google.android"];
 foreach ($skip_referrers as $ref) {
  if (
   str_contains($referrer, $ref) ||
   str_contains($_SERVER["HTTP_REFERER"] ?? "", $ref)
  ) {
   return;
  }
 }

 $pdo
 ->prepare(
  "INSERT INTO analytics (page, ip, referrer, user_agent) VALUES (?,?,?,?)"
 )
 ->execute([$page, $ip, $referrer, $user_agent]);
}

function get_analytics_summary(PDO $pdo): array
{
 return [
  "today" => $pdo
  ->query(
   "SELECT COUNT(*) FROM analytics WHERE DATE(visited_at) = CURDATE()"
  )
  ->fetchColumn(),
  "unique_today" => $pdo
  ->query(
   "SELECT COUNT(DISTINCT ip) FROM analytics WHERE DATE(visited_at) = CURDATE()"
  )
  ->fetchColumn(),
  "week" => $pdo
  ->query(
   "SELECT COUNT(*) FROM analytics WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
  )
  ->fetchColumn(),
  "unique_week" => $pdo
  ->query(
   "SELECT COUNT(DISTINCT ip) FROM analytics WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
  )
  ->fetchColumn(),
  "month" => $pdo
  ->query(
   "SELECT COUNT(*) FROM analytics WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
  )
  ->fetchColumn(),
  "total" => $pdo->query("SELECT COUNT(*) FROM analytics")->fetchColumn(),
 ];
}

function get_top_pages(PDO $pdo, int $limit = 5): array
{
 return $pdo
 ->query(
  "
        SELECT page, COUNT(*) total
        FROM analytics
        WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY page
        ORDER BY total DESC
        LIMIT $limit
    "
 )
 ->fetchAll(PDO::FETCH_ASSOC);
}

function get_daily_views(PDO $pdo, int $days = 7): array
{
 return $pdo
 ->query(
  "
        SELECT DATE(visited_at) date, COUNT(*) total
        FROM analytics
        WHERE visited_at >= DATE_SUB(NOW(), INTERVAL $days DAY)
        GROUP BY DATE(visited_at)
        ORDER BY date ASC
    "
 )
 ->fetchAll(PDO::FETCH_ASSOC);
}

function get_top_referrers(PDO $pdo, int $limit = 5): array
{
 return $pdo
 ->query(
  "
        SELECT referrer, COUNT(*) total
        FROM analytics
        WHERE referrer != '' AND visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY referrer
        ORDER BY total DESC
        LIMIT $limit
    "
 )
 ->fetchAll(PDO::FETCH_ASSOC);
}