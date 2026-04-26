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
        // Získame rozmery obrázka pre výpočet aspect-ratio
        $size = @getimagesize($file);
        $photos[] = [
            'url' => '/assets/img/' . $category . '/' . basename($file),
            'width' => $size ? $size[0] : 800,
            'height' => $size ? $size[1] : 1000
        ];
    }
}

// SIMULÁCIA: Zduplikujeme fotky
$all_photos = [];
if (!empty($photos)) {
    for ($i = 0; $i < 4; $i++) {
        $all_photos = array_merge($all_photos, $photos);
    }
    // Pre zachovanie poradia na mobile (aby neboli rozhádzané)
    // shuffle($all_photos); // Ak chceš presné poradie, vypni shuffle
}


// Zmena hlavičky pre podstránky (aby nebola transparentná nad obrázkami ako na domovskej)
$is_subpage = true; 
$no_padding = true;
require __DIR__ . '/layout/header.php'; 
?>


<!-- Mozaika (Masonry Layout Full-width) -->
<div class="portfolio-masonry-wrapper">
    <a href="/" class="floating-back-btn">&larr; Späť na domov</a>
    
                <section class="masonry-grid-full">
        <?php foreach ($all_photos as $index => $photo): ?>
        <div class="masonry-item-full portfolio-img" style="aspect-ratio: <?php echo $photo['width']; ?> / <?php echo $photo['height']; ?>; background-color: #1a1a1a;">
            <img src="<?php echo $photo['url']; ?>" alt="Fotografia portfólia <?php echo $index; ?>" loading="lazy" data-src="<?php echo $photo['url']; ?>">
        </div>
        <?php endforeach; ?>
    </section>

</div>

<!-- Lightbox Modal -->
<div class="lightbox-overlay" id="lightbox">
    <button class="lightbox-close" id="lightboxClose"><i class="fas fa-times"></i></button>
    <button class="lightbox-prev" id="lightboxPrev"><i class="fas fa-chevron-left"></i></button>
    <img src="" alt="Zväčšená fotografia" class="lightbox-img" id="lightboxImg">
    <button class="lightbox-next" id="lightboxNext"><i class="fas fa-chevron-right"></i></button>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>


