<?php
require_once __DIR__ . '/../db.php';

$category_titles = [
    'eventy' => 'Fotenie eventov',
    'portrety' => 'Fotenie portrétov',
    'sport' => 'Športové fotenie'
];
$title = isset($category_titles[$category]) ? $category_titles[$category] : 'Galéria';

// 1. Skúsime načítať fotky z databázy
$stmt = $pdo->prepare('SELECT s3_url FROM photos WHERE category = ? ORDER BY sort_order ASC, created_at DESC');
$stmt->execute([$category]);
$db_photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($db_photos)) {
    $all_photos = $db_photos;
} else {
    // 2. Ak v DB nič nie je, použijeme pôvodnú simuláciu z lokálnych súborov
    $photos = [];
    $dir = __DIR__ . '/../assets/img/' . $category;
    if (is_dir($dir)) {
        $files = glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
        foreach ($files as $file) {
            $photos[] = '/assets/img/' . $category . '/' . basename($file);
        }
    }

    $all_photos = [];
    if (!empty($photos)) {
        for ($i = 0; $i < 4; $i++) {
            $all_photos = array_merge($all_photos, $photos);
        }
        shuffle($all_photos);
    }
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
                <div class="masonry-item-full portfolio-img">
                    <img src="<?php echo $photo; ?>" alt="Fotografia portfólia <?php echo $index; ?>" loading="lazy" data-src="<?php echo $photo; ?>">
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


