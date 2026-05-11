<?php

$request = $_SERVER['REQUEST_URI'];
$base_path = '/'; // Upravíme podľa potreby pri nasadení na WebSupport

// Jednoduchý router
switch ($request) {
    case $base_path:
    case $base_path . 'domov':
        require __DIR__ . '/views/home.php';
        break;
    case $base_path . 'portfolio':
        require __DIR__ . '/views/portfolio.php';
        break;
    case $base_path . 'o-mne':
        require __DIR__ . '/views/about.php';
        break;
    case $base_path . 'kontakt':
        require __DIR__ . '/views/contact.php';
        break;
    // Kategórie portfólia
    case $base_path . 'portfolio/eventy':
        $category = 'eventy';
        require __DIR__ . '/views/portfolio-detail.php';
        break;
    case $base_path . 'portfolio/portrety':
        $category = 'portrety';
        require __DIR__ . '/views/portfolio-detail.php';
        break;
    case $base_path . 'portfolio/sport':
        $category = 'sport';
        require __DIR__ . '/views/portfolio-detail.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}
