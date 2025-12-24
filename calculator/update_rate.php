<?php

$cacheFile = "cache.json";
$cache = json_decode(file_get_contents($cacheFile), true);

$now = time();

// Rate limit 6 hour
if ($now - $cache["updated_at"] < 21600) {
    die("error:too_soon");
}

$url = "https://www.tgju.org/profile/price_dollar_rl";
$html = file_get_contents($url);

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$node = $xpath->query('//h3[contains(@class,"line") and contains(@class,"clearfix") and contains(@class,"mobile-hide-block")]//span[contains(@class,"value")]/span[1]')->item(0);

if (!$node) {
    die("error:not_found");
}

$price = trim($node->nodeValue);

// Save Cache
$cache["price"] = (int) ((int) str_replace(',', '', $price)) / 10;
$cache["updated_at"] = $now;

file_put_contents($cacheFile, json_encode($cache));

echo $price;
