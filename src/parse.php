<?php
require __DIR__ . '/../vendor/autoload.php';

$files = explode("\n", trim(`find pages -name '*.html*'`));
$urls = [];
$cb = static function(array $match) use (&$entry): string {
    $entry[$match[1]] = trim($match[2]);
    return '';
};
foreach ($files as $path)
{
    $pq = phpQuery::newDocumentFileHTML($path);
    $entry = [];
    preg_replace_callback('~^([\w\x20]+): (.*)$~m', $cb, pq('table.ifsc-info')->text());
    $entry['Address'] = preg_replace_callback('~\s*/?\s*(ZIP Code): (\w+)~', $cb, $entry['Address']);
    $entry = array_filter($entry, 'strlen');
    echo json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
}



