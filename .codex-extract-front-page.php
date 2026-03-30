<?php
libxml_use_internal_errors(true);
$sourcePath = __DIR__ . '/index.html';
$targetBase = __DIR__ . '/wp-content/themes/wipe-clean/template-parts/static';
$html = file_get_contents($sourcePath);
$dom = new DOMDocument();
$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
$xpath = new DOMXPath($dom);
$map = [
    'layout/header.php' => "//header[contains(concat(' ', normalize-space(@class), ' '), ' header ')]",
    'layout/footer.php' => "//footer[contains(concat(' ', normalize-space(@class), ' '), ' footer ')]",
    'front-page/home-hero.php' => "//section[contains(concat(' ', normalize-space(@class), ' '), ' home-hero ')]",
    'front-page/home-wave-group.php' => "//main[contains(concat(' ', normalize-space(@class), ' '), ' main ')]/div[contains(concat(' ', normalize-space(@class), ' '), ' ui-wave-group ')]",
    'front-page/company-preview.php' => "//section[contains(concat(' ', normalize-space(@class), ' '), ' company-preview ')]",
    'front-page/gallery-preview.php' => "//section[contains(concat(' ', normalize-space(@class), ' '), ' gallery-preview ')]",
    'front-page/faq.php' => "//section[contains(concat(' ', normalize-space(@class), ' '), ' faq ')]",
    'front-page/contacts.php' => "//section[contains(concat(' ', normalize-space(@class), ' '), ' contacts ')][1]"
];
foreach ($map as $relativePath => $query) {
    $nodes = $xpath->query($query);
    if (!$nodes || 0 === $nodes->length) {
        fwrite(STDERR, "Missing node for {$relativePath}\n");
        exit(1);
    }
    $output = '';
    foreach ($nodes as $node) {
        $output .= $dom->saveHTML($node);
    }
    $targetPath = $targetBase . '/' . $relativePath;
    if (!is_dir(dirname($targetPath))) {
        mkdir(dirname($targetPath), 0777, true);
    }
    file_put_contents($targetPath, trim($output) . PHP_EOL);
}
