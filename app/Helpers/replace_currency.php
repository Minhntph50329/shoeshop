<?php
/**
 * Script to replace all hardcoded number_format(...)đ patterns
 * with format_price(...) in all blade view files.
 */

$dir = 'c:/laragon/www/shoeshop/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$count = 0;
$details = [];

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    $original = $content;

    // Pattern 1: {{ number_format($var, 0, ',', '.') }}đ
    $content = preg_replace(
        '/\{\{\s*number_format\((.+?),\s*0,\s*\'[,]\',\s*\'[.]\'\s*\)\s*\}\}đ/',
        '{{ format_price($1) }}',
        $content
    );

    // Pattern 2: {{ number_format($var) }}đ
    $content = preg_replace(
        '/\{\{\s*number_format\((.+?)\)\s*\}\}đ/',
        '{{ format_price($1) }}',
        $content
    );

    // Pattern 3: number_format($var, 0, ',', '.') . 'đ'
    $content = preg_replace(
        "/number_format\((.+?),\s*0,\s*'[,]',\s*'[.]'\s*\)\s*\.\s*'đ'/",
        'format_price($1)',
        $content
    );

    // Pattern 4: {{ number_format($var, 0, ',', '.') }} đ (with space)
    $content = preg_replace(
        '/\{\{\s*number_format\((.+?),\s*0,\s*\'[,]\',\s*\'[.]\'\s*\)\s*\}\}\s+đ/',
        '{{ format_price($1) }}',
        $content
    );

    // Pattern 5: {{ number_format($var) }} đ (with space)
    $content = preg_replace(
        '/\{\{\s*number_format\((.+?)\)\s*\}\}\s+đ/',
        '{{ format_price($1) }}',
        $content
    );

    // Pattern 6: Giảm {{ number_format($var) }}đ
    // Already covered by Pattern 2

    if ($content !== $original) {
        file_put_contents($file->getPathname(), $content);
        $count++;
        $relativePath = str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', $file->getPathname());
        echo "Updated: " . $relativePath . PHP_EOL;
    }
}

echo PHP_EOL . "Total files updated: " . $count . PHP_EOL;
