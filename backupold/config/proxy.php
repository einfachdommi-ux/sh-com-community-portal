<?php
declare(strict_types=1);

$feedUrl = "http://147.93.162.150:8280/feed/dedicated-server-stats.xml?code=7NKAX9WxjX0LSm4Z";

$ch = curl_init($feedUrl);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CONNECTTIMEOUT => 8,
  CURLOPT_TIMEOUT => 8,
  CURLOPT_USERAGENT => "ServerStatsProxy/1.0",
]);

$body = curl_exec($ch);
$err  = curl_error($ch);
$code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($body === false || $code < 200 || $code >= 300) {
  http_response_code(502);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode(["ok" => false, "error" => $err ?: ("Upstream HTTP " . $code)], JSON_UNESCAPED_UNICODE);
  exit;
}

header("Content-Type: application/xml; charset=utf-8");
header("Cache-Control: no-store");
echo $body;
?>