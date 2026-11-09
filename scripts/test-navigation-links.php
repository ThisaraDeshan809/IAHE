<?php

define('ABSPATH', __DIR__ . '/fixture/');

function get_header() {}
function get_footer() {}
function get_stylesheet_directory_uri() { return 'https://example.test/subsite/wp-content/themes/divi-child-iahs'; }
function home_url($path = '/') { return 'https://example.test/subsite/' . ltrim($path, '/'); }
function esc_url($url) { return $url; }

ob_start();
require dirname(__DIR__) . '/wp-content/themes/divi-child-iahs/templates/home-modern.php';
$html = ob_get_clean();

$required = array(
    'https://example.test/subsite/programs-qualifications/',
    'https://example.test/subsite/programs-qualifications/#business',
    'https://example.test/subsite/contact/',
    'https://example.test/subsite/about-us/',
    'https://example.test/subsite/services/',
    'https://example.test/subsite/careers/',
);

foreach ($required as $url) {
    if (strpos($html, 'href="' . $url . '"') === false) {
        fwrite(STDERR, "Missing WordPress-aware navigation URL: {$url}\n");
        exit(1);
    }
}

if (preg_match('/href="\/(?:programs-qualifications|contact|about-us|services|careers)/', $html)) {
    fwrite(STDERR, "Found a fragile root-absolute internal navigation URL.\n");
    exit(1);
}

echo "Navigation URL regression test passed.\n";
