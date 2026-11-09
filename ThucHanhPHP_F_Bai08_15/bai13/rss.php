<?php
require __DIR__ . '/../db.php';
$items = db()->query('SELECT title, link, description FROM news ORDER BY id');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (!preg_match('/^[a-z0-9.-]+(?::[0-9]{1,5})?$/iD', $host)) {
    $host = 'localhost';
}
$scheme = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$base = $scheme . '://' . $host . rtrim(dirname($_SERVER['SCRIPT_NAME'], 2), '/') . '/';

$xml = new XMLWriter();
$xml->openMemory();
$xml->setIndent(true);
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement('rss');
$xml->writeAttribute('version', '2.0');
$xml->startElement('channel');
$xml->writeElement('title', 'Thực hành PHP');
$xml->writeElement('link', $base);
$xml->writeElement('description', 'Các bài học PHP từ dữ liệu mẫu MySQL.');
foreach ($items as $item) {
    $xml->startElement('item');
    $xml->writeElement('title', $item['title']);
    $xml->writeElement('link', $base . $item['link']);
    $xml->writeElement('description', $item['description']);
    $xml->endElement();
}
$xml->endElement();
$xml->endElement();
$xml->endDocument();
header('Content-Type: application/rss+xml; charset=UTF-8');
echo $xml->outputMemory();

