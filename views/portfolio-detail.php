<?php 
// Simulácia načítania fotiek pre danú kategóriu
$category_titles = [
    'eventy' => 'Fotenie eventov',
    'portrety' => 'Fotenie portrétov',
    'sport' => 'Športové fotenie'
];
$title = isset($category_titles[$category]) ? $category_titles[$category] : 'Galéria';

$photos = [];
$dir = __DIR__ . '/../assets/img/' . $category;
if (is_dir($dir)) {
    $files = glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
    foreach ($files as $file) {
        $photos[] = '/assets/img/' . $category . '/' . basename($file);
    }
}

// Zmena hlavičky pre podstránky (aby nebola transparentná nad obrázkami ako na domovskej)
$is_subpage = true; 
require __DIR__ . '/layout/header.php'; 
?>

<div class="portfolio-detail-header">
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <a href="/" class="back-link">&larr; Späť na domov</a>
</div>

<!-- Mozaika (Masonry Layout Full-width) -->
<div class="portfolio-masonry-wrapper">
    <section class="masonry-grid-full">
        <?php foreach ($photos as $photo): ?>
        <div class="masonry-item-full">
            <img src="<?php echo $photo; ?>" alt="Fotografia portfólia">
        </div>
        <?php endforeach; ?>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
